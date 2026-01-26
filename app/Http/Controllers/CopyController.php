<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDecisionRequest;
use App\Models\JVcourt;
use App\Models\Room;
use App\Models\State;
use App\Models\Tabs;
use App\Models\Court;
use App\Models\Decision_type;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Redis;

class CopyController extends Controller
{
   
    public function show()
    {
        return view('copier');
    }

    public function fetchCopierData()
    {
        $data['user'] = Auth::user();
        $data['userVCourts'] = User::where(['name'   => $data['user']->name,'active' => 1])->select('vcourt', 'vcourt_name')->get();     

        $data['rooms'] = Room::all();

        $data['states'] = State::all();


        $data['courts'] = Court::whereNotNull('name')->where('name' , '!=' ,' ' )->with('category')->get();
        $data['decisionTypes'] = Decision_type::all();


        $currentCDF = Redis::get("copier::".Auth::id()."::has");
        if ($currentCDF) {
            $raw = Redis::get($currentCDF);
            if ($raw) {
                $data['cfD'] = json_decode($raw, true);
                // تأكد من تمرير المحكمة الصحيحة لجلب التابات والقضاة
                $vCorteCode = $data['cfD']['cfile']['v_corte'];
                $data['tabs'] = $this->bringTabsForVCo($vCorteCode);
                $data['judges'] = JVcourt::where(['vcourt' => $vCorteCode, 'active' => 1])
                                        ->with('person:code,name')->get();
            }
        }

        return response()->json($data);
    }

    public function copyVCFetchData(Request $requ)
    {
        $data['tabs'] = $this->bringTabsForVCo($requ->vcourt);
        $userid = Auth::user()->id;

        // 1. فحص الحجز المسبق لليوزر
        $cfdK = Redis::get("copier::{$userid}::has");
        if ($cfdK) {
            $data['cfD'] = json_decode(Redis::get($cfdK), true);
        }
        
        $data['judges'] = JVcourt::where(['vcourt' =>$requ->vcourt , 'active'=>1])->with('person:code,name')->get();



        $lockKey = "locked::{$requ->vcourt}";
        $maxAttempts = 5; // عدد محاولات الانتظار
        $lockAcquired = false;

        // 2. حلقة الانتظار: حاول الحصول على القفل عدة مرات
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            // محاولة الحجز الذرية
            $lockAcquired = Redis::set($lockKey, 1, 'EX', 10, 'NX');

            if ($lockAcquired) {
                break; // نجح الحجز، اخرج من الحلقة وابدأ العمل
            }

            // إذا فشل، انتظر نصف ثانية (500,000 ميكرو ثانية) قبل المحاولة التالية
            usleep(500000); 
        }

        if (!$lockAcquired) {
            return response()->json(['message' => 'النظام مشغول حالياً، يرجى المحاولة بعد قليل'], 423);
        }

        try {
            // منطقة الأمان (Critical Section)
            $data['cfD'] = $this->bringFirstCFDForCopy($requ->vcourt);
        } finally {
            // تحرير القفل فوراً
            Redis::del($lockKey);
        }

        return response()->json($data);
    }



    protected function bringTabsForVCo($vcourt)
    {
        return $tabs = Tabs::whereJsonContains('courts', "".$vcourt)->select('code',  'description', 'order', 'not_printed', 'group')->get();
    }


    protected function bringFirstCFDForCopy($vcourt)
    {
        $cfilesDs = [];
        // $keys = Redis::keys("*::copy::".$vcourt."::*");
        // $prefix = config('database.redis.options.prefix');

        $cfilesDs = $this->checkReturnedDecisiion( $vcourt);
        if (!$cfilesDs) {
            $allKeys = Redis::smembers("active_decisions_list");
            foreach ($allKeys as $key) {
                // $raw = Redis::get(str_replace($prefix, '', $key));
                if (!str_contains($key, "::copy::{$vcourt}::")) continue;
                $raw = Redis::get($key);
                if ($raw) {
                    $item = json_decode($raw, true);
                    // التأكد أن القرار غير محجوز (reserved)
                    if ($item && (!isset($item['descionD']['reserved']) || !$item['descionD']['reserved'])) {
                        $item['temp_key'] = $key; // حفظ المفتاح للتعامل معه لاحقاً
                        $cfilesDs[] = $item;
                    }
                }
            }
            if (empty($cfilesDs)) return null;
        }
        


        // الترتيب بناءً على رقم القرار
        usort($cfilesDs, function($a, $b) {
            return ($a['descionD']['decision_number'] ?? 0) <=> ($b['descionD']['decision_number'] ?? 0);
        });

        // القرارات المستعجله اولا
        $hurryD = null;
        for ($i = 0; $i < count($cfilesDs); $i++) {
            if ($cfilesDs[$i]['descionD']['hurry']) {
                $hurryD = $cfilesDs[$i];
                break;
            }
        };

        // اختيار أول قرار
        if ($hurryD) $cfD = $hurryD;
        else  $cfD = $cfilesDs[0];
        $itemKey = $cfD['temp_key'];

        // --- الخطوة الأهم: وسم القرار كمحجوز فوراً في Redis ---
        $cfD['descionD']['reserved'] = 1;
        $cfD['descionD']['reservedU'] = Auth::user()->id;
        $cfD['descionD']['reservedUName'] = Auth::user()->name;
        
        // إزالة المفتاح المؤقت قبل الحفظ
        unset($cfD['temp_key']);

        Redis::transaction(function () use ( $itemKey, $cfD) {
            // 4. الحفظ في Redis وربط اليوزر
            Redis::set($itemKey, json_encode($cfD));
            Redis::set("copier::".Auth::id()."::has", $itemKey);
            Redis::srem("returned_decisions_list", $itemKey);
        });

        return $cfD;
    }
    public function releaseCurrentDecision()
    {
        $userid = Auth::id();
        $itemKey = Redis::get("copier::{$userid}::has");

        if ($itemKey) {
            $raw = Redis::get($itemKey);
            if ($raw) {
                $data = json_decode($raw, true);
                // إعادة الحالة لمتاح
                $data['descionD']['reserved'] = 0;
                $data['descionD']['reservedU'] = null;
                $data['descionD']['reservedUName'] = null;

                Redis::transaction(function () use ($data, $userid, $itemKey) {
                    Redis::set($itemKey, json_encode($data));
                    // حذف الرابط باليوزر
                    Redis::del("copier::{$userid}::has");
                });
                broadcast(new \App\Events\NewDecision($data));
            }
        }

        return response()->json(['message' => 'تم تحرير القرار بنجاح']);
    }


    public function saveDraft(StoreDecisionRequest $request) {
        $data = $request->all();
        $userId = Auth::id();

        // بناء المفتاح الأصلي للقرار
        $itemKey = "{$data['cfile']['code']}::copy::{$data['cfile']['v_corte']}::{$data['descionD']['decision_number']}::{$data['cfile']['c_start_year']}";



        $data['descionD']['copied']=1;

        Redis::transaction(function () use ($data, $userId, $itemKey) {
            // 1. تحديث بيانات القرار في Redis (حفظ المسودة)
            Redis::set($itemKey, json_encode($data));

            // 2. ضمان بقاء الرابط مع الناسخ الحالي
            Redis::del("copier::{$userId}::has");
        });

        return response()->json([
            'message' => 'تم حفظ المسودة بنجاح، وهي جاهزة للتدقيق',
            'last_save' => now()->format('H:i:s')
        ]);
    }
    public function saveTempDraft(Request $request) {
        $data = $request->all();
        $userId = Auth::id();

        // بناء المفتاح الأصلي للقرار
        $itemKey = "{$data['cfile']['code']}::copy::{$data['cfile']['v_corte']}::{$data['descionD']['decision_number']}::{$data['cfile']['c_start_year']}";
        $belongToUserKey = Redis::get("copier::{$userId}::has") === $itemKey ;

        if (!Redis::exists($itemKey) || !$belongToUserKey) {
                return response()->json([
                'message' => 'خطأ التحقق من صحة البيانات',
            ]);
        }

        Redis::transaction(function () use ($data, $userId, $itemKey) {
            // 1. تحديث بيانات القرار في Redis (حفظ المسودة)
            Redis::set($itemKey, json_encode($data));

            // 2. ضمان بقاء الرابط مع الناسخ الحالي
            Redis::set("copier::{$userId}::has", $itemKey);
        });

        return response()->json([
            'message' => 'تم حفظ المسودة موقتا',
            'last_save' => now()->format('H:i:s')
        ]);
    }

    protected function checkReturnedDecisiion($vcourt) {
        $allKeys = Redis::smembers("returned_decisions_list");

        foreach ($allKeys as $key) {
            if (!str_contains($key, "::copy::{$vcourt}::")) continue;
            $raw = Redis::get($key);
            if ($raw) {
                $item = json_decode($raw, true);
                // التأكد أن القرار غير محجوز (reserved)
                if ($item && (isset($item['descionD']['reserved']) && ($item['descionD']['reservedU'] == Auth::id()))) {
                    $item['temp_key'] = $key; // حفظ المفتاح للتعامل معه لاحقاً
                    $cfilesDs[] = $item;
                }
            }
        }
        if (empty($cfilesDs)) return null;

        return $cfilesDs;

    }
}
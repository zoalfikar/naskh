<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDecisionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ReviewerController extends Controller
{
    public function show()
    {
        return view('reviewer');
    }

    public function fetchReviewerData()
    {
        $data['user'] = Auth::user();
        $data['userVCourts'] = User::where(['name'   => $data['user']->name,'active' => 1])->select('vcourt', 'vcourt_name')->get();     


        $currentCDF = Redis::get("reviewer::".Auth::id()."::has");
        if ($currentCDF) {
            $raw = Redis::get($currentCDF);
            if ($raw) {
                $data['cfD'] = json_decode($raw, true);
            }
        }


        return response()->json($data);
    }

  
    public function reviewVCFetchData(Request $requ)
    {
        $userid = Auth::user()->id;

        // // 1. فحص الحجز المسبق لليوزر
        // $cfdK = Redis::get("copier::{$userid}::has");
        // if ($cfdK) {
        //     $data['cfD'] = json_decode(Redis::get($cfdK), true);
        // }
        

        // return response()->json("t");


        $lockKey = "lockedR::{$requ->vcourt}";
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
            $data['cfD'] = $this->bringFirstCFDForReview($requ->vcourt);
        } finally {
            // تحرير القفل فوراً
            Redis::del($lockKey);
        }

        return response()->json($data);
    }




    protected function bringFirstCFDForReview($vcourt)
    {
        $cfilesDs = [];
        $allKeys = Redis::smembers("active_decisions_list");

        foreach ($allKeys as $key) {
            if (!str_contains($key, "::copy::{$vcourt}::")) continue;
            $raw = Redis::get($key);
            if ($raw) {
                $item = json_decode($raw, true);
                // التأكد أن القرار غير محجوز (reserved)
                if ($item && ((!isset($item['descionD']['reservedFRevU']) || !$item['descionD']['reservedFRevU']) && $item['descionD']['copied'])) {
                // if ($item['descionD']['copied'] == 1) {
                    $item['temp_key'] = $key; // حفظ المفتاح للتعامل معه لاحقاً
                    $cfilesDs[] = $item;
                }
            }
        }

        if (empty($cfilesDs)) return null;

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
        $cfD['descionD']['reservedFRev'] = 1;
        $cfD['descionD']['reservedFRevU'] = Auth::user()->id;
        $cfD['descionD']['reservedFRevUName'] = Auth::user()->name;
        
        // إزالة المفتاح المؤقت قبل الحفظ
        unset($cfD['temp_key']);

        Redis::transaction(function () use ($itemKey, $cfD) {
            // 4. الحفظ في Redis وربط اليوزر
            Redis::set($itemKey, json_encode($cfD));
            Redis::set("reviewer::".Auth::id()."::has", $itemKey);

        });

        return $cfD;
    }
    // public function releaseCurrentDecision()
    // {
    //     $userid = Auth::id();
    //     $itemKey = Redis::get("copier::{$userid}::has");

    //     if ($itemKey) {
    //         $raw = Redis::get($itemKey);
    //         if ($raw) {
    //             $data = json_decode($raw, true);
    //             // إعادة الحالة لمتاح
    //             $data['descionD']['reserved'] = 0;
    //             $data['descionD']['reservedU'] = null;
    //             $data['descionD']['reservedUName'] = null;

    //             Redis::transaction(function () use ($data, $userid, $itemKey) {
    //                 Redis::set($itemKey, json_encode($data));
    //                 // حذف الرابط باليوزر
    //                 Redis::del("copier::{$userid}::has");
    //             });
    //             broadcast(new \App\Events\NewDecision($data));
    //         }
    //     }

    //     return response()->json(['message' => 'تم تحرير القرار بنجاح']);
    // }

    public function returnDecision(Request $request) {
        $data = $request->all();
        $userId = Auth::user()->id;

        // dd($data['descionD']["returnedNote"]);
        $note = $data['descionD']["returnedNote"];

        // بناء المفتاح الأصلي للقرار
        $itemKey = "{$data['cfile']['code']}::copy::{$data['cfile']['v_corte']}::{$data['descionD']['decision_number']}::{$data['cfile']['c_start_year']}";

        $raw = Redis::get($itemKey);
        $data=[];
        $data=json_decode($raw, true);
        
        $data['descionD']['returned']=1;
        $data['descionD']['reservedFRevU']=null;
        $data['descionD']['reservedFRevUName']=null;
        
        $data['descionD']['returnedNote']= $note;


        Redis::transaction(function () use ($data, $userId, $itemKey) {
            // 1. تحديث بيانات القرار في Redis (حفظ المسودة)
            Redis::set($itemKey, json_encode($data));
            Redis::sadd("returned_decisions_list", $itemKey);
            Redis::del("reviewer::".$userId."::has");
        });

        return response()->json([
            'message' => 'تم اعادة القرار للناسخ للتصحيح',
            'last_save' => now()->format('H:i:s')
        ]);
    }

    public function saveDecision(StoreDecisionRequest $request) {
        $data = $request->all();
        $userId = Auth::id();

        // بناء المفتاح الأصلي للقرار
        $itemKey = "{$data['cfile']['code']}::copy::{$data['cfile']['v_corte']}::{$data['descionD']['decision_number']}::{$data['cfile']['c_start_year']}";



        $data['descionD']['copied']=1;

        Redis::transaction(function () use ($data, $userId, $itemKey) {
            // 1. تحديث بيانات القرار في Redis (حفظ المسودة)
            Redis::del($itemKey);

            Redis::del("copier::{$userId}::has");
        });

        return response()->json([
            'message' => 'تم حفظ المسودة بنجاح، وهي جاهزة للتدقيق',
            'last_save' => now()->format('H:i:s')
        ]);
    }

}

   
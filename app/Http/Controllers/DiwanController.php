<?php

namespace App\Http\Controllers;

use App\Events\NewDecision;
use App\Models\CFile;
use App\Models\VCourt;
use App\Rules\SequenceDecisions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Termwind\Components\Dd;

class DiwanController extends Controller
{
   public function show()
     {
        $vcourts = VCourt::with('catigory')->get();
         return view('diwan');
    }
   public function getVCs() {
        $vcourts = VCourt::with('catigory')->get();
        return response()->json($vcourts);
   }
      public function saveCFile(Request $request) {
      $validated = $request->validate([
            'v_corte' => 'required|exists:v_courts,code',
            'number' => 'required|Numeric|gt:0', 

            'round_year' => 'integer|digits:4|min:1900|max:'.(date('Y') + 1),
            'c_date' => 'required|date|before_or_equal:today',
            'c_start_year' => 'required|integer|digits:4|min:1900|max:'.(date('Y') + 1),
            'decision_number' => ['required','numeric','gt:0',new SequenceDecisions()],
            'decision_date' => 'required|date|before_or_equal:today',
            'urgencyType' => 'required|in:normal,urgent,other',
            'hurry_text' => 'required_if:urgencyType,urgent||nullable|string',  
           'hurry_date' => 'required_if:urgencyType,urgent|date|nullable||before_or_equal:today', 
         ], [
            'v_corte.required' => 'يرجى اختيار المحكمة.',
            'number.required' => 'يرجى إدخال رقم اساس الدعوى.',
            'round_year.required' => 'يرجى إدخال سنة التدوير.',
            'c_date.required' => 'يرجى إدخال تاريخ قيد الدعوى.',
            'c_date.before_or_equal' => 'لا يمكن أن يكون تاريخ قيد الدعوى في المستقبل.',
            'decision_date.before_or_equal' => 'لا يمكن أن يكون تاريخ القرار في المستقبل.',
            'decision_number.required' => 'يرجى إدخال رقم القرار.',
            'decision_date.required' => 'يرجى إدخال تاريخ القرار.',
            'decision_date.before_or_equal' => 'لا يمكن أن يكون تاريخ القرار في المستقبل.',
            'c_start_year.required' => 'يرجى إدخال عام صدور القرار.',
            'c_start_year.hurry_text' => 'يرجى إدخال رقم قرار الاستعجال.',
            'c_start_year.hurry_date' => 'يرجى إدخال تاريخ صدور قرار الاستعجال.',
         ]);

         $validated['user_id'] = Auth::user()->id;

         $data=Arr::only($validated, [
            'v_corte',
            'number',
            'c_start_year',
            'c_date',
            'round_year',
            'user_id',
         ]);
         DB::transaction(function () use ($validated, $data) {
            $cfile=CFile::create($data);
            $cfile->refresh();
            $this->pushDecsionDetoCopy($cfile, [
               'decision_number'=>$validated['decision_number'],
               'decision_date'=>$validated['decision_date'],
               'hurry'=>$validated['urgencyType'] == 'urgent' ? 1 : 0 ,
               'hurry_text'=>$validated['hurry_text'],
               'hurry_date'=>$validated['hurry_date'],
            ]);
         });
         broadcast(new NewDecision($data));
         return response()->json(['message' => 'تم الحفظ بنجاح']);
   }
   private function pushDecsionDetoCopy($cfile,$descionD) {
       
         $key = "{$cfile->code}::copy::{$cfile->v_corte}::{$descionD['decision_number']}::{$cfile->c_start_year}";

         $descionD['hurry']= $descionD['hurry'];
         $descionD['reviewed']=0;
         $descionD['copied']=0;
         $descionD['reserved']=0;
         $descionD['reservedFRev']=0;
         $descionD['reservedUName']=null;
         $descionD['reservedU']=null;
         $descionD['reservedFRevU']=0;
         $descionD['reservedFRevUName']=0;

         $data=['cfile'=>$cfile,'descionD'=>$descionD];
                
         // تخزين البيانات بدون وقت انتهاء (Persistent)
         Redis::set($key, json_encode( $data));

         Redis::sadd("active_decisions_list", $key);
   }

      // private function removeDecsionDetoCopy($cfile,$descionD) {

      //    $key = "{$cfile->code}::copy::{$cfile->v_corte}::{$descionD['decision_number']}::{$cfile->c_start_year}";
       
      //    Redis::del($key);
      // }

      private function removeDecsionDetoCopy($cfile, $descionD) {
         $key = "{$cfile->code}::copy::{$cfile->v_corte}::{$descionD['decision_number']}::{$cfile->c_start_year}";

         // حذف البيانات
         Redis::del($key);
         
         // حذف المفتاح من الفهرس (Set)
         Redis::srem("active_decisions_list", $key);
      }


   public function getActiveDecisions() {




      $decisions = [];
      // $keys = Redis::keys("*::copy::*");
      // $prefix = config('database.redis.options.prefix');

      // 1. جلب كافة المفاتيح من الـ Set مباشرة (سرعة فائقة)
      $keys = Redis::smembers("active_decisions_list");
      $decisionsForCopy = [];
      foreach ($keys as $key) {
         //  $data = Redis::get(str_replace($prefix, '', $key));
         $data = Redis::get($key);
          if ($data) {
              $decisionsForCopy[] = json_decode($data, true);
          }else {
            // ملاحظة ذكية: إذا لم نجد بيانات (ربما انتهى وقت المفتاح)، نحذفه من القائمة
            Redis::srem("active_decisions_list", $key);
        }
      }

      $decisions['decisionsForCopy'] = $decisionsForCopy;

      return response()->json($decisions);
   }


}

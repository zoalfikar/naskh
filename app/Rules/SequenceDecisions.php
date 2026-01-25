<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Redis;

class SequenceDecisions implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $newDecisionNumber = (int) request('decision_number');
        $currentVCorte = request('v_corte'); // تأكد من المسار الصحيح حسب الريكويست المرسل

        // جلب كافة المفاتيح النشطة من Redis
        $keys = Redis::smembers("active_decisions_list");
        $existingNumbersInMyCourt = [];

        foreach ($keys as $key) {
            $parts = explode('::', $key);
            
            // التحقق من أن المفتاح يخص نفس المحكمة الحالية (الجزء الثالث index 2)
            if (isset($parts[2], $parts[3]) && $parts[2] == $currentVCorte) {
                $existingNumbersInMyCourt[] = (int) $parts[3];
            }
        }

        // 1. التحقق من التكرار داخل نفس المحكمة
        if (in_array($newDecisionNumber, $existingNumbersInMyCourt)) {
            $fail('رقم القرار هذا محجوز بالفعل في هذه المحكمة.');
            return;
        }

        // 2. التحقق من التسلسل المنطقي داخل نفس المحكمة
        if (!empty($existingNumbersInMyCourt)) {
            $maxNumber = max($existingNumbersInMyCourt);
            
            // إذا كان الرقم المدخل أكبر من المتوقع (أكبر من أكبر رقم موجود + 1)
            if ($newDecisionNumber > $maxNumber + 1) {
                $fail("خطأ في التسلسل لمحكمتكم. آخر رقم مستخدم هو {$maxNumber}، الرقم المتوقع هو " . ($maxNumber + 1));
            }
            
            // إضافي: منع إدخال أرقام قديمة جداً (فجوات في التسلسل) إذا رغبت
            if ($newDecisionNumber < $maxNumber && !in_array($newDecisionNumber, $existingNumbersInMyCourt)) {
                // اختياري: يمكنك تنبيه المستخدم إذا حاول إدخال رقم أصغر من الماكس ولم يكن محجوزاً
            }
        }
    }
}

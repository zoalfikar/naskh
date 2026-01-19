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
        $newDecisionNumber = (int) $value;
        $year = request('c_start_year');
        $v_corte = request('v_corte');

        $keys = Redis::keys("*::copy::{$v_corte}::*::{$year}");
        $existingNumbers = [];
        foreach ($keys as $key) {
            // تفكيك المفتاح للحصول على رقم القرار (الجزء الرابع في التقسيم ::)
            $parts = explode('::', $key);
            if (isset($parts[3])) {
                $existingNumbers[] = (int) $parts[3];
            }
        }

        if (in_array($newDecisionNumber, $existingNumbers)) {
            $fail('رقم القرار هذا موجود بالفعل في المسودات الحالية بانتظار النسخ.');
            return;
        }
        if (!empty($existingNumbers)) {
            $maxNumber = max($existingNumbers);
            if ($newDecisionNumber > $maxNumber + 1) {
                $fail("يجب أن يكون رقم القرار متسلسلاً. آخر رقم في الريديس هو {$maxNumber}، لذا المتوقع هو " . ($maxNumber + 1));
            }
        }

        
    }
}

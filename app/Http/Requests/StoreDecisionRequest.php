<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class StoreDecisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cfile.code' => 'required',
            'descionD.decision_number' => 'required|numeric',
            'descionD.vjudges' => 'required|array|min:3', // الحد الأدنى لهيئة النقض
            'descionD.tabs' => 'required|array|min:1', // الحد الأدنى لهيئة النقض
        ];
    }


    public function messages(): array{
        return [
            'descionD.vjudges.required'=> 'يرجى اختيار القضاة.',
            'descionD.vjudges.min'=> 'عدد أعداد الهيئة يجب ان يكون ثلاثه على الاقل',
            'descionD.tabs.min'=> 'يجب ان يضمن القرار بند واحد على الاقل',
            'descionD.tabs.required'=> 'يجب تضمين بنود القرار',

        ] ;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $judges = $data['descionD']['vjudges'] ?? [];
            $userId = Auth::id();

            // 1. التحقق من عدم التلاعب بالبيانات (Redis Check)
            $originalKey = "{$data['cfile']['code']}::copy::{$data['cfile']['v_corte']}::{$data['descionD']['decision_number']}::{$data['cfile']['c_start_year']}";
            $belongToUserKey = Redis::get("copier::{$userId}::has") === $originalKey ;

            if (!Redis::exists($originalKey) || !$belongToUserKey) {
                $validator->errors()->add('integrity', 'خطأ في نزاهة البيانات: الملف غير موجود في سجلات الديوان النشطة.');
            }

            // 2. فحص الهيئة الحاكمة
            $presidentCount = 0;
            $separatorCount = 0;
            $oppositeCount = 0;
            $agreeCount = 0;

            foreach ($judges as $j) {
                // عدد الرؤساء
                if ($j['j_desc'] === 'رئيسا') $presidentCount++;
                
                // عدد قضاة الفصل
                if (isset($j['j_serperator']) && $j['j_serperator'] == 1) $separatorCount++;

                // موازنة الموافقين والمخالفين
                if (isset($j['j_oppsoit']) && $j['j_oppsoit'] == 1) {
                    $oppositeCount++;
                } else {
                    $agreeCount++;
                }
            }

            if ($presidentCount !== 1) {
                $validator->errors()->add('judges', 'يجب أن تضم الهيئة رئيساً واحداً فقط.');
            }

            if ($separatorCount !== 1) {
                $validator->errors()->add('judges', 'يجب تحديد قاضي واحد فقط كقاضي فصل.');
            }

            if ($oppositeCount >= $agreeCount) {
                $validator->errors()->add('judges', 'قانونياً: لا يمكن أن يكون عدد المخالفين مساوياً أو أكبر من عدد الموافقين.');
            }
        });
    }
}

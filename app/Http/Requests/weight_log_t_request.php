<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class weight_log_t_request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'weight' => 'required|numeric|min:0.1|max:300', // 数値で範囲0.1~300kgに制限
        ];
    }

    public function attributes()
    {
        return [
            'weight'    =>  '体重',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

                      
            $weight = $this->input('weight');

            // 例: 小数点以下の桁数が3桁以内かどうかをチェック
            if (!preg_match('/^\d+(\.\d{1,3})?$/', $weight)) {
                $validator->errors()->add('weight', '体重は小数点以下3桁以内で入力してください。');
            }
        });
    }

}

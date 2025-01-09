<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class training_detail_t_request extends FormRequest
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
            
        ];
    }

    public function attributes()
    {
        return [
            'reps'        => 'レップ数',            
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            
            $type = $this->input('type');
            $time = $this->input('time');
            $reps = $this->input('reps');
            $weight = $this->input('weight');
            
            
            if ($type == 1) {

                // $repsと$weightが設定されているか確認し、かつ数字か判断する
                if (empty($reps) || !is_numeric($reps)) {
                    $validator->errors()->add('reps', '回数は数字で入力してください。');
                }

                if (empty($weight) || !is_numeric($weight)) {
                    $validator->errors()->add('weight', '重さは数字で入力してください。');
                }
                
            }else{

                // $timeがhh:mm:ssの形式か判断する
                if ($time == '00:00:00' || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $time)) {
                    $validator->errors()->add('time', '時間を設定してください。');
                }
                
            }           

        });
    }

}

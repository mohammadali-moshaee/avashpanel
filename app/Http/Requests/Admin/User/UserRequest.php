<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
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
        if($this->isMethod('post')){
            return [
                'firstname' => 'required',
                'username' => [
                    'required',
                        Rule::unique('users')->whereNull('deleted_at'),
                    ],
                'groups' => 'required|exists:groups,id',
                'password' => 'required|min:8|confirmed',
                'status' => 'required|in:0,1'
            ];

        }else{
            return [
                'firstname' => 'required',
                'username' => [
                    'required',
                        Rule::unique('users')->whereNull('deleted_at')->ignore($this->route('user')),
                    ],
                'groups' => 'required|exists:groups,id',
                'password' => 'nullable|min:8|confirmed',
                'status' => 'required|in:0,1'
            ];
        }
    }

    public function messages(){
        return [
            'username.unique' => 'نام کاربری انتخاب شده قبلا در سامانه ثبت شده است !',
            'password.min' => 'حداقل 8 کاراکتر برای کلمه عبور الزمامی می باشد.',
            'password.confirmed' => 'فیلد کلمه عبور با فیلد تکرار کلمه عبور یکسان نمی باشد'
        ];
    }
}

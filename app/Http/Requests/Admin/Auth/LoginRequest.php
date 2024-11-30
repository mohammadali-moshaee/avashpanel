<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => 'required|string|min:8',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages(){
        return [
            'username.required' => 'نام کاربری اجباری می  باشد.',
            'username.exists' => 'نام کاربری یا کلمه عبور اشتباه می باشد.',
            'password.required' => 'کلمه عبور اجباری می باشد.',
            'password.min' => 'کلمه عبور باید حداقل 8 کاراکتر باشد.',
            'g-recaptcha-response.required' => 'لطفا کپجا را وارد کنید'
        ];
    }

}

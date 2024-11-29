<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
                'name' => [
                    'required',
                    Rule::unique('products')->whereNull('deleted_at')
                ],
                'categories' => 'nullable|array',
                'categories.*' => 'exists:product_categories,id',
                'attributes' => 'nullable|array',
                'attributes.*' => 'nullable',
            ];

        }else{
            return [
                'name' => [
                    'required',
                    Rule::unique('products')->whereNull('deleted_at')->ignore($this->route('product'))
                ],
                'options' => 'nullable|array|max:255',
                'options.*' => 'nullable|array|max:255',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:product_categories,id',
            ];
            
        }
    }

    public function messages(){
        return [
            'name.required' => 'عنوان مشخصه اجباری می باشد',
            'name.unique' => 'عنوان   مشخصه انتخابی قبلا ثبت شده است',
        ];
    }
}

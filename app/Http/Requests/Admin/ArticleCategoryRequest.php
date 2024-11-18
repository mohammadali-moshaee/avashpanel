<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleCategoryRequest extends FormRequest
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
                    Rule::unique('article_categories')->whereNull('deleted_at'),
                ],
                'published' => 'in:0,1',
                'parent_id' => [
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !\DB::table('article_categories')
                            ->where('id', $value)
                            ->whereNull('deleted_at')
                            ->exists()) {
                            $fail('مقدار ' . $attribute . ' باید یا 0 باشد یا شناسه معتبری از دسته‌بندی‌های موجود.');
                        }
                    },
                ]
            ];

        }else{
            return [
                'name' => [
                    'required',
                    Rule::unique('article_categories')->whereNull('deleted_at')->ignore($this->route('id')),
                ],
                'published' => 'in:0,1',
                'parent_id' => [
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !\DB::table('article_categories')
                            ->where('id', $value)
                            ->whereNull('deleted_at')
                            ->exists()) {
                            $fail('مقدار ' . $attribute . ' باید یا 0 باشد یا شناسه معتبری از دسته‌بندی‌های موجود.');
                        }
                    },
                ]
            ];

        }
    }

    public function messages(){
        return [
            'name.required' => 'عنوان اجباری می باشد !',
            'name.unique' => 'عنوان انتخابی شما قبلا ثبت شده است',
            'published.in' => 'مقدار وارد شده برای فیلد انتشار صحیح نمی باشد'
        ];
    }
}

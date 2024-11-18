<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
                'title' => [
                'required',
                    Rule::unique('articles')->whereNull('deleted_at'),
                ],
                'categories' => 'required|array',
                'published' => 'in:0,1',
                'categories.*' => 'exists:article_categories,id',
                'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
                'pinnedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            ];
        }else{
            return [
                'title' => [
                'required',
                    Rule::unique('articles')->whereNull('deleted_at')->ignore($this->route('id')),
                ],
                'categories' => 'required|array',
                'published' => 'in:0,1',
                'categories.*' => 'exists:article_categories,id',
                'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
                'pinnedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            ];

        }
    }

    public function messages(){
        return [
            'title.required' => 'عنوان اجباری می باشد',
            'categories.required' => 'دسته بندی اجباری می باشد',
            'published.in' => 'مقدار وارد شده برای فیلد انتشار صحیح نمی باشد',
            'title.unique' => 'عنوان وارد شده قبلا استفاده شده است.'
        ];
    }
}

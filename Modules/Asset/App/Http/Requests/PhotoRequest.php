<?php

namespace Modules\Asset\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PhotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'upload_file' => 'required|image|max:2048', // Max size 2MB
            'price' => 'required|numeric|min:0',
            'tags' => 'array',
            'tags.*' => 'string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Define the custom messages.
     */
    public function messages(): array
    {
        return [
            'upload_file.required' => 'آپلود فایل عکس اجباری است.',
            'upload_file.image' => 'فایل انتخاب شده باید یک تصویر معتبر باشد.',
            'upload_file.max' => 'حجم فایل نباید بیشتر از 2 مگابایت باشد.',
            'price.required' => 'وارد کردن قیمت اجباری است.',
            'price.numeric' => 'قیمت باید به صورت عددی وارد شود.',
            'price.min' => 'قیمت نمی‌تواند کمتر از صفر باشد.',
            'tags.array' => 'تگ‌ها باید به صورت آرایه وارد شوند.',
            'tags.*.string' => 'هر تگ باید یک رشته معتبر باشد.',
            'tags.*.max' => 'طول هر تگ نباید بیشتر از 255 کاراکتر باشد.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
            'description.string' => 'توضیحات باید یک متن معتبر باشد.',
            'description.max' => 'توضیحات نباید بیشتر از 500 کاراکتر باشد.',
        ];
    }

    /**
     * Override the failedValidation method to return a JSON response.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'اعتبارسنجی ناموفق بود.',
                'errors' => collect($validator->errors()->all()), // Return only the error messages as an array
            ], 422)
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

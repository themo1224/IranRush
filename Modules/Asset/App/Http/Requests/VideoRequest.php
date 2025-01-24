<?php

namespace Modules\Asset\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'upload_file' => 'required|mimetypes:video/mp4,video/x-matroska|max:102400',
            'price' => 'required|numeric|min:0',
            'tags' => 'array',
            'tags.*' => 'string|max:255',
            'category_id' => 'exists:categories,id',
        ];
    }

    /**
     * Define the custom messages.
     */
    public function messages(): array
    {
        return [
            'upload_file.required' => 'آپلود فایل ویدئو اجباری است.',
            'upload_file.mimetypes' => 'فقط فرمت‌های mp4 و mkv مجاز هستند.',
            'upload_file.max' => 'حجم فایل نباید بیشتر از 100 مگابایت باشد.',
            'price.required' => 'وارد کردن قیمت اجباری است.',
            'price.numeric' => 'قیمت باید به صورت عددی وارد شود.',
            'price.min' => 'قیمت نمی‌تواند کمتر از صفر باشد.',
            'tags.array' => 'تگ‌ها باید به صورت آرایه وارد شوند.',
            'tags.*.string' => 'هر تگ باید یک رشته معتبر باشد.',
            'tags.*.max' => 'طول تگ نباید بیشتر از 255 کاراکتر باشد.',
            'category_id.required' => 'انتخاب دسته‌بندی اجباری است.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
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
                'message' => 'Validation failed.',
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

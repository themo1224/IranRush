<?php

namespace Modules\Asset\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AudioRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    /**
     * Define the custom messages.
     */
    public function messages(): array
    {
        return [
            'upload_file.required' => 'آپلود فایل صوتی اجباری است.',
            'upload_file.mimetypes' => 'فقط فرمت‌های mp3 و mpeg مجاز هستند.',
            'upload_file.max' => 'حجم فایل نباید بیشتر از 50 مگابایت باشد.',
            'price.required' => 'وارد کردن قیمت اجباری است.',
            'price.numeric' => 'قیمت باید به صورت عددی وارد شود.',
            'price.min' => 'قیمت نمی‌تواند کمتر از صفر باشد.',
            'tags.array' => 'تگ‌ها باید به صورت آرایه وارد شوند.',
            'tags.*.string' => 'هر تگ باید یک رشته معتبر باشد.',
            'tags.*.max' => 'طول هر تگ نباید بیشتر از 255 کاراکتر باشد.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
            'description.string' => 'توضیحات باید یک متن معتبر باشد.',
            'description.max' => 'توضیحات نباید بیشتر از 500 کاراکتر باشد.',
            'duration.integer' => 'مدت زمان باید یک عدد صحیح باشد.',
            'duration.min' => 'مدت زمان باید حداقل 1 ثانیه باشد.',
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

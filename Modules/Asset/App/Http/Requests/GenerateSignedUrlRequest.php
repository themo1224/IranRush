<?php

namespace Modules\Asset\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GenerateSignedUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust this if you need specific authorization logic
    }

    public function rules(): array
    {
        return [
            'upload_file' => 'required|file|max:10048|mimes:jpeg,png,jpg,mp4,mp3',
            'price' => 'required|numeric|min:0',

        ];
    }

    public function messages(): array
    {
        return [
            'upload_file.required' => 'فایل آپلود اجباری است.',
            'upload_file.file' => 'فایل انتخاب شده معتبر نیست.',
            'upload_file.max' => 'حجم فایل نباید بیشتر از10  مگابایت باشد.',
            'upload_file.mimes' => 'فقط فرمت‌های jpeg, png, jpg, mp4, mp3 مجاز هستند.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت باید عددی باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',
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
}

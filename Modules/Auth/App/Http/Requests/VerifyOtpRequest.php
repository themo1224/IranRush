<?php

namespace Modules\Auth\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required|string',
            'name' => 'required|string',
            'otp' => 'required|string|min:6|max:6',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'شماره تلفن اجباری است.',
            'phone_number.string' => 'شماره تلفن باید یک رشته معتبر باشد.',
            'name.required' => 'نام اجباری است.',
            'name.string' => 'نام باید یک رشته معتبر باشد.',
            'otp.required' => 'کد تایید اجباری است.',
            'otp.string' => 'کد تایید باید یک رشته معتبر باشد.',
            'otp.min' => 'کد تایید باید حداقل 6 کاراکتر باشد.',
            'otp.max' => 'کد تایید باید حداکثر 6 کاراکتر باشد.',
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

<?php

namespace Modules\Auth\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required|string|unique:users,phone_number',
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'شماره تلفن اجباری است.',
            'phone_number.string' => 'شماره تلفن باید یک رشته معتبر باشد.',
            'phone_number.unique' => 'شماره تلفن وارد شده قبلاً ثبت شده است.',
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

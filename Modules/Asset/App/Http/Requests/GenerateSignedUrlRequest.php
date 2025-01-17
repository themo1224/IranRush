<?php

namespace Modules\Asset\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateSignedUrlRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'fileName' => 'required|string|max:255',
            'filetype' => 'required|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

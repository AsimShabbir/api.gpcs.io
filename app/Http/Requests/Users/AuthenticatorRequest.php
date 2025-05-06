<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Enums\StatusCode;
class AuthenticatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            //
            'contact_number' => 'required|string',
            'password' => 'nullable|string',

        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'code' => StatusCode::UNAUTHORIZED,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], StatusCode::UNAUTHORIZED));

    }
    public function messages()
    {
        return [
            'contact_number.required' => 'The phone number field is required.',
            'contact_number.max' => 'The phone number  field may not be greater than :max characters.',
            //'password.required' => 'The password field is required.',
        ];
    }
}

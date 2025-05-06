<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoordinateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }
    // public function failedValidation(Validator $validator)
    // {
    //     $this->validateModel($validator);
    // }

}

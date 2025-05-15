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

class GpscRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'countrycode' => 'nullable|string|max:255',
            'firstcode' => 'nullable|string|max:255',
            'secondcode' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:5000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'label' => 'nullable|string|max:5000',
           // 'paid' => 'nullable'|'integer'|'max:2',
            //'amount' => 'nullable'|'integer'|'max:99999',
        ];
    }
    // public function failedValidation(Validator $validator)
    // {
    //     $this->validateModel($validator);
    // }

}

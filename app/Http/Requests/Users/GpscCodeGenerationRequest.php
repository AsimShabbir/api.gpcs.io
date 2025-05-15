<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\GpcsCode;
use App\Enums\StatusCode;
use App\Traits\ModelValidatorTrait;
use Illuminate\Support\Facades\Storage;

class GpscCodeGenerationRequest extends FormRequest
{
    use ModelValidatorTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {

        return [
            'user_id' => 'nullable|integer|max:5000',
            'country_code' => 'nullable|string|max:255',
            'first_part' => 'nullable|string|max:255',
            'second_part' => 'nullable|string|max:255',
            'gpcscode' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'label' => 'nullable|string|max:5000',
            'is_deleted' => 'nullable|integer|max:5000',
            'verified' => 'nullable'|'integer'|'max:2',
            'paid' => 'nullable'|'integer'|'max:2',
            'amount' => 'nullable'|'integer'|'max:99999',

        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validateModel($validator);
    }
    public function save()
    {

        $gpcs_code = new GpcsCode();
        $gpcs_code->fill($this->all());
        $gpcs_code->save();
        return $gpcs_code;
    }

}

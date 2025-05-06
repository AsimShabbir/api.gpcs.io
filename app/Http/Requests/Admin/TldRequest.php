<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Enums\StatusCode;
use App\Traits\RoleTrait;
use App\Traits\ModelValidatorTrait;
use App\Models\TldCountryCode;
use Illuminate\Validation\Rule;
class TldRequest extends FormRequest
{
    use RoleTrait, ModelValidatorTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->isAdmin();
    }

     /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $tld_country_code = intval($this->route('id'));
        return [
            'domain_country_code' => 'required|string|max:100',Rule::unique('tld_country_codes')->ignore($tld_country_code),
            'map_code' => 'required|string|max:500',
            'country_name' => 'nullable|string|max:300',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->validateModel($validator);
    }

    public function save()
    {
        $section = new Section();
        $section->fill($this->all());
        $section->save();
    }
    public function update($section)
    {
        $section->fill($this->all());
        $section->save();
        return $section;
    }
}

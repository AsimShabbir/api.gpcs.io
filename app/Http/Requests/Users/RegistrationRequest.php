<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;
use App\Models\Role;
use App\Enums\StatusCode;
use App\Traits\ModelValidatorTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'otp_code' => 'nullable|string|max:16',
            'contact_number' => 'required|string|max:255|unique:users',
            'role_id' => 'required|integer|max:9999999',
            'password' => 'nullable|string|max:255',
            'verified' => ['nullable', 'integer', 'max:1', 'default:0'],

        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validateModel($validator);
    }
    public function save()
    {

        $user = new User();
        $user->fill($this->all());
        $user->password = "test";
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp_code = $otp;
        $user->save();
        $this->saveuserrole($user, $this->role_id);
        return $user;
    }
    public function saveuserrole($user, $role_id)
    {
        $role = Role::find($role_id);
        $user->roles()->attach($role);

    }
}

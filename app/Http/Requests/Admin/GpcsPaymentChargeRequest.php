<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\GpcsPaymentCharge;
use App\Enums\StatusCode;
use App\Traits\ModelValidatorTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GpcsPaymentChargeRequest extends FormRequest
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
        'amount' => [
            'nullable',
            'integer',
            'max:5000',
            Rule::unique('gpcs_payment_charges')->where(function ($query) {
                return $query->where('currency', $this->input('currency'));
            }),
        ],
        'currency' => [
            'nullable',
            'string',
            'max:255',
            Rule::unique('gpcs_payment_charges')->where(function ($query) {
                return $query->where('amount', $this->input('amount'));
            }),
        ],
        'is_active'  => 'nullable|integer|max:5000',
        'created_by' => 'nullable|integer|max:5000',
        'updated_by' => 'nullable|integer|max:5000',
    ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validateModel($validator);
    }
    public function save()
    {
        $auth_user = Auth::User();

        $user_id = $auth_user->id;

        $gpcs_payment_charge = new GpcsPaymentCharge();
        $gpcs_payment_charge->fill($this->all());
        $gpcs_payment_charge->created_by = $user_id;
        $gpcs_payment_charge->updated_by = $user_id;


        $gpcs_payment_charge->save();
        return $gpcs_payment_charge;
    }
    public function update($gpcs_payment_charge)
    {
        $auth_user = Auth::User();
        $user_id = $auth_user->id;
        $gpcs_payment_charge->fill($this->all());
        $gpcs_payment_charge->is_active=1;
        $gpcs_payment_charge->updated_by = $user_id;
        $gpcs_payment_charge->save();
        return $gpcs_payment_charge;
    }

}

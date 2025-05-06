<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Enums\StatusCode;

trait ModelValidatorTrait
{
    protected function validateModel(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'code' => StatusCode::UNPROCESSABLE_ENTITY,
            'message'   => 'Validation error(s)',
            'data'      => $validator->errors()
        ], StatusCode::UNPROCESSABLE_ENTITY));
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidatesStripePayments
{
    /**
     * Validate one-time donation request.
     */
    public function validateOneTimeDonation($request)
    {
        $validator = Validator::make($request->all(), [
            'fundraisername' => 'required',
            'fundraiseremail' => 'required',
            'fundraisercontact_number' => 'required',
            'amount' => 'required|numeric',
            'currency' => 'required|string|in:usd,eur,gbp',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return null; // No validation errors
    }

    /**
     * Validate recurring donation request.
     */
    public function validateRecurringDonation($request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|in:usd,eur,gbp',
            'payment_method' => 'required|string',
            'plan' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return null; // No validation errors
    }
}

<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\api\v1\BaseController;
use App\Traits\ValidatesStripePayments;
use App\Traits\HandlesStripeCurrency;
use Illuminate\Http\Request;

//use App\Models\Subscription as UserSubscription;
use Illuminate\Support\Facades\Log;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\Subscription;

class StripePaymentController extends BaseController
{
    use ValidatesStripePayments, HandlesStripeCurrency;

    public function processOneTimeDonation(Request $request)
    {
        $validationResponse = $this->validateOneTimeDonation($request);
        if ($validationResponse) {
            return $validationResponse;
        }
        //dd($request->currency);
        $stripeSecretKey = $this->getStripeKeyForCurrency($request->currency);
        //dd($stripeSecretKey);
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        $amount =  $request->amount;// + $request->tipsgiven;
        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'return_url' => 'https://your-website.com/return',
            ]);

            return response()->json(['message' => 'Donation successful!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // public function processRecurringDonation(Request $request)
    // {
    //     $validationResponse = $this->validateRecurringDonation($request);
    //     if ($validationResponse) {
    //         return $validationResponse;
    //     }

    //     $stripeSecretKey = $this->getStripeKeyForCurrency($request->currency);
    //     \Stripe\Stripe::setApiKey($stripeSecretKey);

    //     try {
    //         $customer = \Stripe\Customer::create(['email' => $request->email]);

    //         $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method);
    //         $paymentMethod->attach(['customer' => $customer->id]);

    //         \Stripe\Customer::update($customer->id, [
    //             'invoice_settings' => ['default_payment_method' => $request->payment_method],
    //         ]);

    //         $plan = \Stripe\Plan::create([
    //             'amount' => $request->amount * 100,
    //             'currency' => $request->currency,
    //             'interval' => 'day',
    //             'product' => [
    //                 'name' => 'Recurring Donation Plan for ' . $request->email,
    //             ],
    //         ]);

    //         $subscription = \Stripe\Subscription::create([
    //             'customer' => $customer->id,
    //             'items' => [['plan' => $plan->id]],
    //             'expand' => ['latest_invoice.payment_intent'],
    //             'currency' => $request->currency,
    //             'description' => "Recurring Donation for " . $request->email,
    //         ]);

    //         $subscriptionData = new UserSubscription();
    //         $subscriptionData->user_id = '2';
    //         $subscriptionData->stripe_customer_id = $customer->id;
    //         $subscriptionData->subscription_id = $subscription->id;
    //         $subscriptionData->plan_id = $plan->id;
    //         $subscriptionData->amount = $request->amount;
    //         $subscriptionData->currency = $request->currency;
    //         $subscriptionData->payment_method_Id = $request->payment_method;
    //         $subscriptionData->next_payment_date = date('Y-m-d H:i:s', $subscription->current_period_end);
    //         $subscriptionData->last_processed_at = now();
    //         $subscriptionData->save();

    //         return response()->json(['message' => 'Your donation has been processed successfully!', 'subscription' => $subscription], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }

}

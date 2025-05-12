<?php

namespace App\Traits;

trait HandlesStripeCurrency
{
    /**
     * Get the Stripe Secret Key based on the currency.
     *
     * @param string $currency
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getStripeKeyForCurrency($currency)
    {
        $currencies = config('currencies.currencies'); // Ensure your config file exists and has currency data
//dd($currencies);
        if (array_key_exists($currency, $currencies)) {
            return $currencies[$currency]['stripe_key'];
        }

        throw new \InvalidArgumentException("Invalid currency: $currency");
    }
}

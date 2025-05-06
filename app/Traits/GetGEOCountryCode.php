<?php

namespace App\Traits;

trait GetGEOCountryCode
{
    //
    public function generate($countryCode)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        $letter1 = $letters[random_int(0, strlen($letters) - 1)];
        $letter2 = $letters[random_int(0, strlen($letters) - 1)];
        $number1 = $numbers[random_int(0, strlen($numbers) - 1)];
        $number2 = $numbers[random_int(0, strlen($numbers) - 1)];
        $number3 = $numbers[random_int(0, strlen($numbers) - 1)];
        $number4 = $numbers[random_int(0, strlen($numbers) - 1)];
        $letter3 = $letters[random_int(0, strlen($letters) - 1)];
        $letter4 = $letters[random_int(0, strlen($letters) - 1)];

        return "{$countryCode}-{$letter1}{$letter2}{$number1}{$number2}-{$letter3}{$letter4}{$number3}{$number4}";
    }
}

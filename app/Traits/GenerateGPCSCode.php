<?php
namespace App\Traits;

trait GenerateGPCSCode
{

    public function generateCode($currentCode = null)
    {
        if ($currentCode === null) {
            return 'AA00';
        }

        $numbers = substr($currentCode, 2, 2);
        $letters = substr($currentCode, 0, 2);

        $numbers++;

        if ($numbers > 99) {
            $numbers = 0;
            $letters = $this->incrementLetters($letters);
        }

        return $letters . str_pad($numbers, 2, '0', STR_PAD_LEFT);
    }

    private function incrementLetters($letters)
    {
        $secondChar = ord($letters[1]);
        $firstChar = ord($letters[0]);

        if ($secondChar < ord('Z')) {
            $secondChar++;
        } else {
            $secondChar = ord('A');
            if ($firstChar < ord('Z')) {
                $firstChar++;
            } else {
                //Handle overflow if you need to go beyond ZZ.
                $firstChar = ord('A'); // or throw an exception, or handle the overflow in some other way.
            }
        }

        return chr($firstChar) . chr($secondChar);
    }
}

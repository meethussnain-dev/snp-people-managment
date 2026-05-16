<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SouthAfricanIdNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = (string) $value;

        if (! preg_match('/^\d{13}$/', $value)) {
            return false;
        }

        $digits = array_map('intval', str_split($value));
        $oddSum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
        $evenDigits = $digits[1] . $digits[3] . $digits[5] . $digits[7] . $digits[9] . $digits[11];
        $evenSum = array_sum(array_map('intval', str_split((string) ((int) $evenDigits * 2))));
        $checksum = (10 - (($oddSum + $evenSum) % 10)) % 10;

        return $checksum === $digits[12];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid South African ID number.';
    }
}

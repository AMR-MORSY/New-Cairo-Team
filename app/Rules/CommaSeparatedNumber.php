<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CommaSeparatedNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $numericValue = str_replace(',', '', $value);
       if( !is_numeric($numericValue) && $numericValue > 0)
        {
            $fail("The $attribute must be a valid number");

        }

      
    }
}

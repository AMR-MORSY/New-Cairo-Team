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
    public function __construct(
        private float $min = 0,
        private float $max = 999999.99,
        private bool $allowZero = true,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $number = str_replace(',', '', trim((string) $value));
        $label = str_replace('_', ' ', $attribute);

        // digits with an optional dot and up to 2 decimals - no sign, no exponent
        if (! preg_match('/^\d+(\.\d{1,2})?$/', $number)) {
            $fail("The $label must be a number with up to 2 decimals.");
            return;
        }

        $number = (float) $number;

        if (! $this->allowZero && $number == 0) {
            $fail("The $label must be greater than zero.");
        } elseif ($number < $this->min || $number > $this->max) {
            $fail("The $label must be between " . number_format($this->min) . ' and ' . number_format($this->max, 2) . '.');
        }
    }
}

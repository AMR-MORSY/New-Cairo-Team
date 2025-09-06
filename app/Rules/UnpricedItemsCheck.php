<?php

namespace App\Rules;

use Closure;
use App\Models\Modification\UnpricedItem;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UnpricedItemsCheck implements ValidationRule,DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected array $data;

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $unpricedItem = UnpricedItem::where('item',$this->data['item'])->exists();
        if (!$unpricedItem && $value == 0) {
            $fail("The {$attribute} is required");
        }
    }
}

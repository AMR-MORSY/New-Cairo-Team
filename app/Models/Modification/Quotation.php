<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use App\Models\Modification\PriceListItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quotation extends Model
{
    protected $table = "quotations";

    protected $hidden = ['created_at', 'updated_at'];


    public function modification():BelongsTo
    {
        return $this->belongsTo(Modification::class);
    }


    public function priceListItems():BelongsToMany
    {
        return $this->belongsToMany(PriceListItem::class)->withPivot(['supply_price','install_price','item_price','quantity','scope']);
    }
}

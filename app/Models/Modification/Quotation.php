<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\MailListItem;
use App\Models\Modification\Modification;
use App\Models\Modification\PriceListItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quotation extends Model
{
    protected $table = "quotations";

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['modification_id'];


    public function modification(): BelongsTo
    {
        return $this->belongsTo(Modification::class);
    }


    public function priceListItems(): BelongsToMany
    {
        return $this->belongsToMany(PriceListItem::class)->withPivot(['id','supply_price', 'install_price', 'item_price', 'quantity', 'scope']);
    }

    public function mailListItems(): BelongsToMany
    {
        return $this->belongsToMany(MailListItem::class)->withPivot(['id','supply_price', 'install_price', 'item_price', 'quantity', 'scope']);
    }


    public function sumPriceListItems()
    {
        return $this->priceListItems()->sum('item_price');
    }
    public function sumMailListItems()
    {
        return $this->mailListItems()->sum('item_price');
    }

    public function quotationCost()
    {
        return 'EGP '. number_format( $this->sumMailListItems() + $this->sumPriceListItems(),2);
    }
}

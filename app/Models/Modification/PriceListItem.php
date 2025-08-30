<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceListItem extends Model
{
    protected $table="price_list_items";

    protected $hidden =['created_at','updated_at'];

    protected $fillable=[
        'item','description','type','supply','installation','sup_int','unit'
    ];

    protected $casts=[
        'supply'=>'decimal:8,2',
        'installation'=>'decimal:8,2',
        'sup_int'=>'decimal:8,2'
        
    ];

    public function quotations():BelongsToMany
    {
        return $this->belongsToMany(Quotation::class)->withPivot(['supply_price','install_price','item_price','quantity','scope']);
    }
}

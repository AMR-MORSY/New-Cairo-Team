<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OverPoInvoice extends Model
{

    protected $hidden = ['updated_at', 'created_at'];

    protected $table = "over_po_invoices";

    protected $fillable = [
        'modification_reservation_id',
        'amount',
        'reserved_at'

    ];
    
    protected $casts = [

        "amount" => 'decimal:2',
        "reserved_at"=>'datetime'
       
    ];

    public function modificationReservation():BelongsTo
    {
        return $this->belongsTo(ModificationReservation::class);
    }
}

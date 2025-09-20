<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\ModificationReservation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
      protected $hidden = ['updated_at', 'created_at'];

    protected $table = "invoices";

    protected $fillable = [
        'modification_reservation_id',
        'amount',

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

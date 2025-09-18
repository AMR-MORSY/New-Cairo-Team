<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OverPo extends Model
{

    protected $hidden = ['updated_at', 'created_at'];

    protected $table = "over_pos";

    protected $fillable = [
        'modification_reservation_id',
        'amount',

    ];
    
    protected $casts = [

        "in_progress" => 'decimal:2',
       
    ];

    public function modificationReservation():BelongsTo
    {
        return $this->belongsTo(ModificationReservation::class);
    }
}

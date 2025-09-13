<?php

namespace App\Models\Modification;

use App\Models\Modification\PO;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModificationReservation extends Model
{

    protected $hidden = ['updated_at', 'created_at'];

    protected $casts = [
        'amount' => 'decimal:2',
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
    ];



    protected $fillable = [
        'modification_id',
        'PO_id',
        'status',
        'amount',
        'reserved_at',
        'expires_at'
    ];

    public function modification(): BelongsTo
    {
        return $this->belongsTo(Modification::class);
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(PO::class);
    }

     // Check if reservation is expired
    public function getIsExpiredAttribute()
    {
        return $this->expires_at < now();
    }
}

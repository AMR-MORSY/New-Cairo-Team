<?php

namespace App\Models\Modification;

use App\Models\Modification\PO;
use App\Models\Modification\OverPo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use App\Models\Modification\OverPoInvoice;
use App\Models\Modification\PurchaseOrder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'purchase_order_id',
        'status',
        'amount',
        'reserved_at',
        'expires_at'
    ];

      public function getExpiresAtForUserAttribute()
    {
        return $this->expires_at->setTimezone(Auth::user()->time_zone ?? 'Africa/Cairo')->format('Y-m-d H:i:s');
    }

       public function getReservedAtForUserAttribute()
    {
        return $this->expires_at->setTimezone(Auth::user()->time_zone ?? 'Africa/Cairo')->format('Y-m-d H:i:s');
    }
   

    public function modification(): BelongsTo
    {
        return $this->belongsTo(Modification::class);
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class,'purchase_order_id');
    }

    // Check if reservation is expired
    public function getIsExpiredAttribute()
    {
        return $this->expires_at < now();
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_string($value)) {
                    // Convert comma to dot for decimal separator
                    $value = str_replace(',', '', $value);
                    return (float)$value;
                }
                return $value;
            },
            get: function ($value) {

                return (float) $value;
            }

        );
    }

    public function overPo():HasOne
    {
        return $this->hasOne(OverPoInvoice::class);
    }
}

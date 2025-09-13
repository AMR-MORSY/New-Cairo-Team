<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Modification\ModificationReservation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PO extends Model
{
    protected $hidden = ['updated_at'];

    protected $table="pos";



    protected $fillable = [
        'subcontractor_id',
        'amount',
        'status',
        'type',
        'po_number'
    ];


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


    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ModificationReservation::class);
    }

    // Get available quantity (total - reserved)
    public function getAvailableAmountAttribute() ////////this will return the available amount  in the PO to the user in case the available amount is insufficient 
    {
        $this->refreshReservedAmount();
        return $this->amount - $this->in_progress;
    }

    // Refresh reserved quantity by checking active reservations
    public function refreshReservedAmount()
    {
        $activeReserved = $this->reservations()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->sum('amount');

        $this->update(['in_progress' => $activeReserved]);

        return $this;
    }

     // Check if quantity is available for reservation
    public function hasAvailableAmount($requestedAmount)
    {
        return $this->available_amount >= $requestedAmount;
    }
}

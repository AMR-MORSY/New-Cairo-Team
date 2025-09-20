<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Modification\ModificationReservation;
use Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    protected $hidden = ['updated_at'];

    protected $table = "purchase_orders";



    protected $fillable = [
        'subcontractor_id',
        'amount',
        'status',
        'type',
        'on_hand',
        'po_number',
        'in_progress'
    ];

    protected $casts = [

        "in_progress" => 'decimal:2',
        "on_hand" => 'decimal:2',
        'invoiced' => 'decimal:2'
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

    protected function inProgress(): Attribute
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

    // Get available amount  (total - reserved+Invoiced)
    public function getAvailableAmount() ////////this will return the available amount  in the PO to the user 
    {
        $this->refreshReservedAmount();
        $onHand = $this->amount - $this->in_progress + $this->invoiced;
        $this->update(['on_hand' => $onHand]);

        return $onHand;
    }

    // Refresh in progress work orders amount by checking active reservations
    public function refreshReservedAmount()
    {
        $activeReserved = $this->reservations()
            ->where('status', 'active')
            ->where('expires_at', '>', now())->sum('amount');



        $this->update(['in_progress' => $activeReserved]);

        return $this;
    }

    // Check if quantity is available for reservation
    public function hasAvailableAmount($requestedAmount)
    {
        return $this->available_amount >= $requestedAmount;
    }
}

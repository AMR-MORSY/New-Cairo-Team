<?php

namespace App\Models\Modification;

use App\Enums\Zones;
use App\Models\Area;
use App\Models\User;
use App\Models\Zone;
use App\Models\Site\Site;
use App\Models\Modification\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Modification extends Model
{

    use SoftDeletes;
    protected $table = "modifications";

    protected $fillable = [
        'area_id',
        'zone_id',
        'action_owner',
        'site_code',
        'subcontractor_id',
        'requester_id',
        'description',
        'pending',
        'modification_status_id',
        'project_id',
        'request_date',
        'd6_date',
        'cw_date',
        'wo_code',
        'final_cost',
        'est_cost',
        'reported',
        'reported_at',
        'deleted_at',
        'invoice_id',
        'month',
        'year',
    ];


    protected $casts = [
        "request_date" => 'datetime:Y-m-d',
        'd6_date' => 'datetime:Y-m-d',
        'cw_date' => 'datetime:Y-m-d',
        'reported_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime',
        'est_cost' => 'decimal:2',
        'final_cost' => 'decimal:2'


    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_code');
    }
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function actionOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_owner');
    }
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class);
    }


    protected function createWOCode()
    {
        $lastModification = Modification::withTrashed()->where("zone_id", $this->zone_id)->get();
        $lastModificationId = 0;
        if (count($lastModification) > 0) {
            $lastModificationId = $lastModification->last()->id;
        }

        $zone = Zones::getCodeByValue($this->zone->code);
        $W_o_Code = $zone . "-00" . $lastModificationId + 1;
        $this->wo_code = $W_o_Code;
    }
    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            $model->createWOCode();
            $model->month = $model->request_date->monthName;
            $model->year = $model->request_date->year;
        });
    }


    protected function cwDate(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (!empty($value) || $value != null) {
                    return $value;
                }
                return null;
            },
            get: function ($value) {
                if ($value === null) {
                    return null;
                }
                return $this->asDateTime($value);
            }
        );
    }
    protected function d6Date(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (!empty($value) || $value != null) {
                    return $value;
                }
                return null;
            },
            get: function ($value) {
                if ($value === null) {
                    return null;
                }
                return $this->asDateTime($value);
            }
        );
    }

    protected function reportedAt(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (!empty($value) || $value != null) {
                    return $value;
                }
                return null;
            },
            get: function ($value) {
                if ($value === null) {
                    return null;
                }
                return $this->asDateTime($value);
            }
        );
    }

    protected function finalCost(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_string($value)) {
                    // Convert comma to dot for decimal separator
                    $value = str_replace(',', '', $value);
                    return $value;
                }
                // $value = str_replace(',', '', $value);
                // return $value;
            },

        );
    }
    protected function estCost(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_string($value)) {
                    // Convert comma to dot for decimal separator
                    $value = str_replace(',', '', $value);
                    return $value;
                }
                // $value = str_replace(',', '', $value);
                // return $value;
            },

        );
    }
}

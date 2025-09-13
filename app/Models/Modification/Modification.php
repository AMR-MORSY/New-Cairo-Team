<?php

namespace App\Models\Modification;

use Carbon\Carbon;
use App\Enums\Zones;
use App\Models\Area;
use App\Models\User;
use App\Models\Zone;
use App\Models\Site\Site;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use App\Models\Modification\Quotation;
use App\Models\Modification\Requester;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Modification\ModificationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Modification\ModificationReservation;
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
        "request_date" => 'date',
        'd6_date' => 'date',
        'cw_date' => 'date',
        'reported_at' => 'date',
        'deleted_at' => 'datetime',
        'est_cost' => 'decimal:2',
        'final_cost' => 'decimal:2' /////Database driver behavior: MySQL returns numeric fields as strings to preserve precision so we float the returned value in accessor method below


    ];


    public function reservations():HasMany
    {
        return $this->hasMany(ModificationReservation::class);
    }

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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Requester::class);
    }

    public function modification_status(): BelongsTo
    {
        return $this->belongsTo(ModificationStatus::class);
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
            $requestDate = Carbon::parse($model->request_date);
            $model->month = $requestDate->monthName;
            $model->year = $requestDate->year;
        });
    }



    protected function requestDate(): Attribute
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
                return $value;
            }
        );
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
                return $value;
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
                return $value;
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
                return $this->asDate($value);
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
                    return (float)$value;
                }
                return $value;
            },
            get: function ($value) {

                return (float) $value;
            }

        );
    }
    protected function estCost(): Attribute
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


    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
}

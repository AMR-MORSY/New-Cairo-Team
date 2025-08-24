<?php

namespace App\Models\Site;

use App\Models\Area;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{

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
        'final_coast',
        'est_coast',
        'reported',
        'reported_at',
        'deleted_at',
        'invoice_id',
        'month',
        'year',
    ];


    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function actionOwner()
    {
        return $this->belongsTo(User::class, 'action_owner');
    }
}

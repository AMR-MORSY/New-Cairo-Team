<?php

namespace App\Models\Modification;

use App\Models\Modification\PO;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\PurchaseOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcontractor extends Model
{
   protected $table='subcontractors';

    protected $hidden=['created_at','updated_at'];


    public function pos():HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

     public function getSubcontractorAvailablePOs(string|null $projectName = null)
    {
        $POs=$this->pos()->where('type', $projectName)->where('status', 'open')->get();/////taking into account that the subcontractor might have more than one open PO

      
        return $POs;
    }

}

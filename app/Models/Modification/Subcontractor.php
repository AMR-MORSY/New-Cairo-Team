<?php

namespace App\Models\Modification;

use App\Models\Modification\PO;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\PurchaseOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\IsLookupOption;

class Subcontractor extends Model
{
    use IsLookupOption;

   protected $table='subcontractors';

    protected $hidden=['created_at','updated_at'];

    /** Maintained through the admin panel, so both columns are assignable. */
    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];



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

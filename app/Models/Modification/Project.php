<?php

namespace App\Models\Modification;

use App\Enums\ModificationProjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\IsLookupOption;

class Project extends Model
{
    use IsLookupOption;


     protected $hidden = ['created_at', 'updated_at'];

     /** Maintained through the admin panel, so both columns are assignable. */
     protected $fillable = ['name', 'is_active'];

     protected $casts = ['is_active' => 'boolean'];



     public function getProjectPOName()
     {
          //    $projectName = Project::find($project_id)->name;

          $POName = ModificationProjects::getPOByValue($this->name);
          return $POName;
     }


     public function modifications():HasMany
     {
        return  $this->hasMany(Modification::class);

     }
}

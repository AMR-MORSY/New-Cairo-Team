<?php

namespace App\Models\Modification;

use App\Enums\ModificationProjects;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

     protected $hidden = ['created_at', 'updated_at'];


     public function getProjectPOName()
     {
          //    $projectName = Project::find($project_id)->name;

          $POName = ModificationProjects::getPOByValue($this->name);
          return $POName;
     }
}

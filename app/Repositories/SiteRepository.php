<?php

namespace App\Repositories;

use ArrayIterator;
use App\Models\Site\Site;
use App\Models\Site\Cascade;
use App\Contracts\SiteInterface;
use Illuminate\Database\Eloquent\Collection;

class SiteRepository
{

    public function indirectCascades($directCascades): array
    {
        $inDirectCascades = [];

        $directCascadesArray = $directCascades->toArray();


        $directCascadesContainer = new ArrayIterator($directCascadesArray);
        $i = 0;
        while (count($directCascadesContainer) > 0) {
            $nodal_cascades = Cascade::where("nodal_code", $directCascadesContainer[$i]["cascade_code"])->get();
            if (count($nodal_cascades) > 0) {
                foreach ($nodal_cascades as $cascade) {
                    $site = Site::where('site_code', $cascade['cascade_code'])->first();
                    if ($site) {
                        array_push($inDirectCascades, $site);
                        $directCascadesContainer->append($cascade);
                    }
                }
            }

            $directCascadesContainer->offsetUnset($i);
            $i++;
        }
        return $inDirectCascades;
    }


    private function adjustCascadedNodalsArray($cascaded_nodals)
    {
          $cascaded_nodals = array_map(function ($nodal) {
            $site_name = Site::where('site_code', $nodal)->first()->site_name;
            $nodal = "<>{$nodal}_{$site_name}";

            return $nodal;
        }, $cascaded_nodals);

        return $cascaded_nodals;

    }

    public function nodals($nodal_code)
    {
        $cascaded_nodals[] = $nodal_code;
        $nodals[] = $nodal_code;

        $nodalsContainer = new ArrayIterator($nodals);

        $i = 0;
        while (count($nodalsContainer) > 0) {
            $nodal_cascade = Cascade::where('cascade_code', $nodalsContainer[$i])->first();
            if ($nodal_cascade) {
                $cascaded_nodals[] = $nodal_cascade->nodal_code;
                $nodalsContainer->append($nodal_cascade->nodal_code);
            }
            $nodalsContainer->offsetUnset($i);
            $i++;
        }

        $cascaded_nodals=$this->adjustCascadedNodalsArray($cascaded_nodals);

      
        return $cascaded_nodals;
    }
}

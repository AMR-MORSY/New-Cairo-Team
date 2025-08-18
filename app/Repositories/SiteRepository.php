<?php

namespace App\Repositories;

use ArrayIterator;
use App\Models\Site\Site;
use App\Models\Site\Cascade;
use App\Contracts\SiteInterface;
use Illuminate\Database\Eloquent\Collection;

class SiteRepository implements SiteInterface
{

    public function all()
    {
        $sites = Site::all();

        return $sites;
    }


    public function directCascades(Collection $directCascades): array
    {

        $newDirectCascades = [];
        foreach ($directCascades as $cascade) {
            $site = [];
            $site['details'] = Site::where('site_code', $cascade->cascade_code)->first();
            $nodal_cascades = Cascade::where('nodal_code', $cascade['cascade_code'])->get();
            $site['count'] = $nodal_cascades->count();
            array_push($newDirectCascades, $site);
        }



        return $newDirectCascades;
    }
    public function indirectCascades(object $directCascades): array
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
                    array_push($inDirectCascades, $site);
                    $directCascadesContainer->append($cascade);
                }
            }

            $directCascadesContainer->offsetUnset($i);
            $i++;
        }
        return $inDirectCascades;
    }
    public function create() {}
}

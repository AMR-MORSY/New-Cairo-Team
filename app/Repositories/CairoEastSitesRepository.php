<?php

namespace App\Repositories;

use App\Contracts\SiteInterface;

class CairoEastSitesRepository
{
    public static $siteRepository;


    public function __construct(SiteInterface $siteRepository)
    {
        self::$siteRepository=$siteRepository;
        
    }



     public static function dispatchSites($sites)
    {

        $sites =  $sites->map(function ($nodal)  {
            $nodal->battery_brand = $nodal->batteries ? $nodal->batteries->pluck('batteries_brand')->implode("\n") : '';
            $nodal->no_strings = $nodal->batteries ? $nodal->batteries->pluck('no_strings')->implode("\n") : '';
            $nodal->count_cascades = count(self::$siteRepository->indirectCascades($nodal->cascades))  + $nodal->cascades->count();
            $nodal->installation_date = $nodal->batteries ? $nodal->batteries->pluck('installation_date')->implode("\n") : '';
            return $nodal;
        });

        return $sites;

        

       
    }


}
<?php

namespace App\Enums;


enum ModificationRequesters: string
{
    case SITE_MANAGEMENT = "Site Management";
    case CIVIL_TEAM = "Civil Team";
    case MAINTENANCE = "Maintenance";
    case RADIO = "Radio";
    case ROLLOUT = "Rollout";
    case TRANSMISSION = "Transmission";
    case GA = "GA";
    case SOC = "Soc";
    case SHARING_TEAM = "Sharing team";
}

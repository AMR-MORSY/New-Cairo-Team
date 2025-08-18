<?php

namespace App\Enums;

enum SiteTypies: string
{
    case OUTDOOR = "Outdoor";
    case SHELTER = 'Shelter';
    case MICRO = 'Micro';
    case PICO = 'Pico';
    case MOBILE_STATION = 'Mobile Station';
    case LDN = 'LDN';
    case TP = 'TP';
}

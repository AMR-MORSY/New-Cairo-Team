<?php

namespace App\Enums;

enum SiteTypies: string
{
    case MACRO = "Macro";
    case INDOOR='Indoor';
    case MICRO = 'Micro';
    case PICO = 'Pico';
    case MOBILE_STATION = 'Mobile Station';
    case LDN = 'LDN';
    case TP = 'TP';
}

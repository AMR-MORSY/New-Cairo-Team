<?php

namespace App\Enums;

enum Guest: string
{
    case OEG = "OG";
    case VF = 'VF';
    case ET = "ET";
    case WE = "WE";
    case VF_OG = "VF+OG";
    case ET_VF = "ET+VF";
    case OG_ET = "OG+ET";
    case ET_WE = "ET+WE";
    case VF_WE = "VF+WE";
    case OG_WE = "OG+WE";
    case OG_WE_ET="OG+WE+ET";
    case OG_WE_VF="OG+WE+VF";
    case VF_WE_ET="VF+WE+ET";
    case OG_ET_VF="OG+ET+VF";
}


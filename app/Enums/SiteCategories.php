<?php

namespace App\Enums;

enum SiteCategories: string
{
    case NDL = "NDL";
    case LDN = "LDN";
    case BSC = 'BSC';
    case TP = 'TP';
    case VIP = 'VIP';
    case VIP_NDL = 'VIP + NDL';
    case NORMAL = "Normal";
}

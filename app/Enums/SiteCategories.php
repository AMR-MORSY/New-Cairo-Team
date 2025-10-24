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
    case GOLDEN_SQUARE="G.SQR";
    case NDL_GSQR="NDL + G.SQR";
    case TP_G_SQR="TP + G.SQR";
    case VIP_G_SQR="VIP + G.SQR";
    case VIP_NDL_G_SQR="VIP + NDL + G.SQR";
}

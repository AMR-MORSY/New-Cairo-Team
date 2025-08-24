<?php

namespace App\Enums;


enum ModificationProjects: string

{
    case SITE_DISMANTLE = "Site Dismantle";
    case NTRA = "NTRA";
    case UNSAFE_EXISTING = "Unsafe Existing";
    case B2B = "B2B";
    case LTE = "LTE";
    case G5 = "5G";
    case SHARING = "Sharing";
    case SITE_SECURITY = "Site Security";
    case ADDING_SEC = "Adding Sec";
    case TDD = "TDD";
    case POWER_MODIFICATION = "Power Modification";
    case L1_MODIFICATION = "L1 Modification";
    case TX_MODIFICATION = "Tx Modification";
    case G2G = "G2G";
    case NEW_SITES = "New Sites";
}

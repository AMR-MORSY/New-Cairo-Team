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


    

    public function projectPoType()
    {
        return match ($this) {
            self::TDD => "ROLLOUT",
            self::LTE => "ROLLOUT",
            self::G5 => "ROLLOUT",
            self::B2B => "B2B"
        };
    }

       public static function getPOByValue(string $value): ?string /// self::tryFrom($value) attempts to create an enum instance from the string value If successful, it calls getCodes() on that instance.If the value doesn't match any enum case, tryFrom() returns null, and the method returns null
    {
        $project = self::tryFrom($value);
        return $project?->projectPoType();
    }



}
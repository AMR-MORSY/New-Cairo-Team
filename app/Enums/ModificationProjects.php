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


    private const PO_TYPE_MAP = [
        self::TDD->value => "ROLLOUT",
        self::LTE->value => "ROLLOUT",
        self::G5->value => "ROLLOUT",
        self::B2B->value => "B2B",
        self::ADDING_SEC->value => "ROLLOUT",
        self::G2G->value => "MOD_G2G",
        self::SITE_DISMANTLE->value => "MOD",
        self::NEW_SITES->value => 'ROLLOUT',
        self::SHARING->value => "MOD",
        self::SITE_SECURITY->value => "MOD",
        self::NTRA->value => "MOD",
        self::UNSAFE_EXISTING->value => "MOD",
        self::POWER_MODIFICATION->value => "MOD",
        self::L1_MODIFICATION->value => "MOD",
        self::TX_MODIFICATION->value => "MOD",

    ];



    public static function getPOByValue(string $value): ?string /// self::tryFrom($value) attempts to create an enum instance from the string value If successful, it calls getCodes() on that instance.If the value doesn't match any enum case, tryFrom() returns null, and the method returns null
    {

        return self::PO_TYPE_MAP[$value] ?? null;
    }
}

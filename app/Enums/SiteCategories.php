<?php

namespace App\Enums;

enum SiteCategories: string
{
    case NDL = "NDL";
    case LDN = "LDN";
    case BSC = 'BSC';
    case TP = 'TP';
    case TP_NDL = 'TP + NDL';
    case TP_VIP = 'TP + VIP';
    case VIP = 'VIP';
    case VIP_NDL = 'VIP + NDL';
    case NORMAL = "Normal";
    case GOLDEN_SQUARE = "G.SQR";
    case NDL_GSQR = "NDL + G.SQR";
    case TP_G_SQR = "TP + G.SQR";
    case TP_G_SQR_NDL = "TP + G.SQR + NDL";
    case VIP_G_SQR = "VIP + G.SQR";
    case VIP_NDL_G_SQR = "VIP + NDL + G.SQR";
    case VIP_NDL_TP = "VIP + NDL + TP";
    case TP_G_SQR_VIP = "TP + G.SQR + VIP";
    case TP_G_SQR_VIP_NDL = "TP + G.SQR + VIP + NDL";

    /**
     * The category a site takes once it is a nodal (NDL): "TP" -> "TP + NDL",
     * "Normal" -> "NDL". A category that already carries NDL comes back as it is.
     *
     * LDN and BSC have no NDL variant and also come back as they are. Null
     * only when the value is not a category at all, so the caller can report
     * the site instead of writing something that is not a case.
     *
     * The match is exhaustive: a new case without an arm throws
     * \UnhandledMatchError rather than being silently mapped.
     * tryFrom() is used to convert a string to an enum case, so the caller can pass either.
     */
    public static function mapSiteNodality(self|string $category): ?string
    {
        // the old switch compared the string to enum cases, which never match in PHP
        $category = $category instanceof self ? $category : self::tryFrom($category);

        if ($category === null) {
            return null;
        }

        return match ($category) {
            self::NORMAL => self::NDL->value,
            self::TP => self::TP_NDL->value,
            self::VIP => self::VIP_NDL->value,
            self::TP_VIP => self::VIP_NDL_TP->value,
            self::GOLDEN_SQUARE => self::NDL_GSQR->value,
            self::TP_G_SQR => self::TP_G_SQR_NDL->value,
            self::VIP_G_SQR => self::VIP_NDL_G_SQR->value,
            self::TP_G_SQR_VIP => self::TP_G_SQR_VIP_NDL->value,
            self::NDL, self::TP_NDL, self::VIP_NDL, self::NDL_GSQR,
            self::TP_G_SQR_NDL, self::VIP_NDL_G_SQR, self::VIP_NDL_TP, self::TP_G_SQR_VIP_NDL => $category->value,

            self::LDN, self::BSC => $category->value, // no NDL variant
        };
    }
}

<?php

namespace App\Enums;

enum Zones: string
{
    case CAIRO_SOUTH = "Cairo South";
    case CAIRO_EAST = "Cairo East";
    case CAIRO_NORTH = "Cairo North";
    case GIZA = "Giza";
    case NORTH_UPPER = 'North Upper';
    case RED_SEA = 'Red Sea';
    case SOUTH_UPPER = 'South Upper';
    case SINAI = 'Sinai';
    case ALEX = "Alex";
    case DELTA_NORTH = 'Delta North';
    case DELTA_SOUTH = "Delta South";
    case NORTH_COAST = "North Coast";


    public function getCodes()
    {
        return match ($this) {
            self::CAIRO_SOUTH => 'CS',
            self::CAIRO_EAST => 'CE',
            self::CAIRO_NORTH => 'CN',
            self::GIZA => 'GZ',
            self::DELTA_NORTH => 'DN',
            self::DELTA_SOUTH => "DS",
            self::NORTH_COAST => "NC",
            self::ALEX => "AL",
            self::NORTH_UPPER => 'NU',
            self::RED_SEA => 'RE',
            self::SOUTH_UPPER => 'SU',
            self::SINAI => 'SI',
        };
    }
     public static function getCodeByValue(string $value): ?string /// self::tryFrom($value) attempts to create an enum instance from the string value If successful, it calls getCodes() on that instance.If the value doesn't match any enum case, tryFrom() returns null, and the method returns null
    {
        $zone = self::tryFrom($value);
        return $zone?->getCodes();
    }

    public static function HGLIZones() //////static means the function could be called in the class without creating object ex: Zones::HGLIZones
    {
        return [
            self::CAIRO_SOUTH->value,
            self::CAIRO_EAST->value,
            self::CAIRO_NORTH->value,
            self::GIZA->value

        ];
    }


    public static function AGLIZones(): array
    {
        return [
            self::DELTA_NORTH->value,
            self::DELTA_SOUTH->value,
            self::NORTH_COAST->value,
            self::ALEX->value

        ];
    }

    public static function NGLIZones()
    {
        return [
            self::NORTH_UPPER->value,
            self::SOUTH_UPPER->value,
            self::RED_SEA->value,
            self::SINAI->value

        ];
    }
}

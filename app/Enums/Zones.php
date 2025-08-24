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

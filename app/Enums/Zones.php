<?php

namespace App\Enums;

/**
 * Operational zones, backed by their short code - the code is what is stored
 * in users.zone, sites.zone and modifications.zone, so display wording can
 * change freely without touching data.
 *
 * Adding a zone means: one case, one meta() arm. The match is exhaustive, so
 * a case without a meta() arm throws \UnhandledMatchError, and the guard test
 * in tests/Unit/ZonesEnumTest.php turns that into a CI failure.
 */
enum Zones: string
{
    case CAIRO_SOUTH = 'CS';
    case CAIRO_EAST  = 'CE';
    case CAIRO_NORTH = 'CN';
    case GIZA        = 'GZ';
    case NORTH_UPPER = 'NU';
    case SOUTH_UPPER = 'SU';
    case RED_SEA     = 'RE';
    case SINAI       = 'SI';
    case DELTA_NORTH = 'DN';
    case DELTA_SOUTH = 'DS';
    case NORTH_COAST = 'NC';
    case ALEX        = 'AL';

    /** @return array{0: string, 1: Teams} label, owning team */
    private function meta(): array
    {
        return match ($this) {
            self::CAIRO_SOUTH => ['Cairo South', Teams::HGLI],
            self::CAIRO_EAST  => ['Cairo East',  Teams::HGLI],
            self::CAIRO_NORTH => ['Cairo North', Teams::HGLI],
            self::GIZA        => ['Giza',        Teams::HGLI],

            self::NORTH_UPPER => ['North Upper', Teams::NGLI],
            self::SOUTH_UPPER => ['South Upper', Teams::NGLI],
            self::RED_SEA     => ['Red Sea',     Teams::NGLI],
            self::SINAI       => ['Sinai',       Teams::NGLI],

            self::DELTA_NORTH => ['Delta North', Teams::AGLI],
            self::DELTA_SOUTH => ['Delta South', Teams::AGLI],
            self::NORTH_COAST => ['North Coast', Teams::AGLI],
            self::ALEX        => ['Alex',        Teams::AGLI],
        };
    }

    public function label(): string
    {
        return $this->meta()[0];
    }

    public function team(): Teams
    {
        return $this->meta()[1];
    }

    /** @return array<int, self> */
    public static function forTeam(Teams $team): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $zone) => $zone->team() === $team
        ));
    }

    /** @return array<string, string> value => label, for select inputs */
    public static function options(?Teams $team = null): array
    {
        $cases = $team ? self::forTeam($team) : self::cases();

        return array_reduce(
            $cases,
            fn (array $c, self $z) => $c + [$z->value => $z->label()],
            []
        );
    }
}

<?php

namespace App\Enums;

/**
 * The complete role set. Replaces the 21 team- and zone-prefixed roles:
 * the job is the role, the scope comes from users.team / users.zone.
 *
 * Permission sets below are the UNION of the previously-diverged copies,
 * matching what ModificationRolesAndPermissionsSeeder always intended.
 */
enum UserRoles: string
{
    case SUPER_ADMIN = 'super_admin';
    case MODIFICATION_ADMIN = 'modification_admin';
    case SITE_ADMIN = 'site_admin';
    case TEAM_MANAGER = 'team_manager';
    case ZONE_MANAGER = 'zone_manager';
    case SITE_ENGINEER = 'site_engineer';
    case MAINTENANCE_ENGINEER = 'maintenance_engineer';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN          => 'Super Admin',
            self::MODIFICATION_ADMIN   => 'Modification Admin',
            self::SITE_ADMIN           => 'Site Admin',
            self::TEAM_MANAGER         => 'Team Manager',
            self::ZONE_MANAGER         => 'Zone Manager',
            self::SITE_ENGINEER        => 'Site Engineer',
            self::MAINTENANCE_ENGINEER => 'Maintenance Engineer',
        };
    }

    /** @return array<int, string> permission names */
    public function permissions(): array
    {
        $M = ModificationPermissions::class;
        $S = SitePermissions::class;

        return match ($this) {
            self::SUPER_ADMIN => array_merge(
                array_column($M::cases(), 'value'),
                array_column($S::cases(), 'value'),
            ),

            self::MODIFICATION_ADMIN => [
                $M::VIEW_MODIFICATIONS->value,
                $M::CREATE_MODIFICATIONS->value,
                $M::UPDATE_MODIFICATIONS->value,
            ],

            self::SITE_ADMIN => [
                $S::CREATE_NEW_SITES->value,
                $S::UPDATE_SITES->value,
            ],

            self::TEAM_MANAGER => [
                $M::VIEW_AREA_MODIFICATIONS->value,
                $M::CREATE_MODIFICATIONS->value,
                $M::UPDATE_MODIFICATIONS->value,
            ],

            self::ZONE_MANAGER => [
                $M::VIEW_AREA_MODIFICATIONS->value,
                $M::CREATE_MODIFICATIONS->value,
                $M::UPDATE_ZONE_MODIFICATIONS->value,
                $M::UPDATE_OWN_MODIFICATIONS->value,
            ],

            self::SITE_ENGINEER => [
                $M::VIEW_AREA_MODIFICATIONS->value,
                $M::CREATE_MODIFICATIONS->value,
                $M::UPDATE_OWN_MODIFICATIONS->value,
            ],

            self::MAINTENANCE_ENGINEER => [
                $S::VIEW_ZONE_SITES->value,
            ],
        };
    }

    /**
     * Maps every old prefixed role name onto its replacement.
     * Consumed by the Phase 4 data migration.
     *
     * @return array<string, self>
     */
    public static function legacyMap(): array
    {
        $map = [
            'modification_admin'      => self::MODIFICATION_ADMIN,
            'site_admin'              => self::SITE_ADMIN,
            'CE_maintenance_engineer' => self::MAINTENANCE_ENGINEER,
        ];

        foreach (Teams::cases() as $team) {
            $map[$team->value.'_team_manager']  = self::TEAM_MANAGER;
            $map[$team->value.'_site_engineer'] = self::SITE_ENGINEER;
        }

        foreach (Zones::cases() as $zone) {
            $map[$zone->value.'_zone_manager'] = self::ZONE_MANAGER;
        }

        return $map;
    }
}

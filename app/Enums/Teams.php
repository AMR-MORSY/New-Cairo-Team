<?php

namespace App\Enums;

/**
 * Operational teams. These are the only values valid on users.team,
 * sites.team and modifications.team.
 *
 * Cross-team authority (modification_admin, site_admin, super_admin) is a
 * ROLE, not a team - see App\Enums\UserRoles. A user with global authority
 * has team = null.
 */
enum Teams: string
{
    case HGLI = 'HGLI';
    case NGLI = 'NGLI';
    case AGLI = 'AGLI';

    public function label(): string
    {
        return match ($this) {
            self::HGLI => 'HGLI Team',
            self::NGLI => 'NGLI Team',
            self::AGLI => 'AGLI Team',
        };
    }

    /** @return array<int, Zones> */
    public function zones(): array
    {
        return Zones::forTeam($this);
    }

    /** @return array<string, string> value => label, for select inputs */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $c, self $t) => $c + [$t->value => $t->label()],
            []
        );
    }
}

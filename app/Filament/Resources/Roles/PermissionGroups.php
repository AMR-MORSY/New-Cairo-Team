<?php

namespace App\Filament\Resources\Roles;

use App\Enums\ModificationPermissions;
use App\Enums\SitePermissions;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

/**
 * Splits the permission list into the panels the role form shows.
 *
 * The form and the pages that save it both need the same grouping, and each
 * panel holds its own checkbox state rather than the `permissions` relation:
 * several checkbox lists bound to one relation would each sync it on save and
 * wipe the others' choices.
 */
class PermissionGroups
{
    /** Form field name for a group. */
    public static function field(string $key): string
    {
        return 'permission_group_' . $key;
    }

    /**
     * group key => ['label' => string, 'options' => [id => label]]
     *
     * @return array<string, array{label: string, options: array<int, string>}>
     */
    public static function all(): array
    {
        $permissions = Permission::query()->orderBy('name')->get();

        $known = [
            'modifications' => ['Modifications', array_column(ModificationPermissions::cases(), 'value')],
            'sites' => ['Sites', array_column(SitePermissions::cases(), 'value')],
        ];

        $groups = [];
        $claimed = [];

        foreach ($known as $key => [$label, $names]) {
            $matching = $permissions->whereIn('name', $names);

            if ($matching->isNotEmpty()) {
                $groups[$key] = ['label' => $label, 'options' => static::options($matching)];
                $claimed = array_merge($claimed, $matching->pluck('name')->all());
            }
        }

        $rest = $permissions->reject(fn ($p) => in_array($p->name, $claimed, true));

        if ($rest->isNotEmpty()) {
            $groups['other'] = ['label' => 'Other', 'options' => static::options($rest)];
        }

        return $groups;
    }

    /** Every permission id the form knows about, across all groups. */
    public static function allIds(): array
    {
        return collect(static::all())
            ->flatMap(fn (array $group) => array_keys($group['options']))
            ->all();
    }

    /** @param  Collection<int, Permission>  $permissions */
    protected static function options(Collection $permissions): array
    {
        return $permissions
            ->sortBy('name')
            ->mapWithKeys(fn (Permission $p) => [
                $p->id => Str::of($p->name)->replace('_', ' ')->ucfirst()->toString(),
            ])
            ->all();
    }
}

<?php

namespace App\Filament\Resources\Roles\Concerns;

use App\Filament\Resources\Roles\PermissionGroups;

/**
 * Moves the role form's grouped checkboxes to and from the `permissions`
 * relation. The groups are plain form state, so they have to be filled on the
 * way in and synced on the way out.
 */
trait SyncsPermissionGroups
{
    /** @var array<int, int> permission ids chosen across every group */
    protected array $chosenPermissions = [];

    /** Spread the role's permissions across the group fields. */
    protected function fillPermissionGroups(array $data, ?object $record): array
    {
        $held = $record ? $record->permissions->pluck('id')->all() : [];

        foreach (PermissionGroups::all() as $key => $group) {
            $ids = array_keys($group['options']);
            $data[PermissionGroups::field($key)] = array_values(array_intersect($held, $ids));
        }

        return $data;
    }

    /** Collect the groups, then drop them so Eloquent never sees them. */
    protected function takePermissionGroups(array $data): array
    {
        $chosen = [];

        foreach (array_keys(PermissionGroups::all()) as $key) {
            $field = PermissionGroups::field($key);
            $chosen = array_merge($chosen, (array) ($data[$field] ?? []));
            unset($data[$field]);
        }

        $this->chosenPermissions = array_values(array_unique(array_map('intval', $chosen)));

        return $data;
    }

    protected function syncPermissionGroups(?object $record): void
    {
        $record?->syncPermissions(
            \Spatie\Permission\Models\Permission::query()
                ->whereIn('id', $this->chosenPermissions)
                ->get()
        );
    }
}

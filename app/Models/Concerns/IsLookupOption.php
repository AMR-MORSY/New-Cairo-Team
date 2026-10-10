<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for the small tables the modification forms pick from:
 * subcontractors, projects, requesters, actions.
 *
 * Entries are retired with `is_active` rather than deleted, because
 * modifications.subcontractor_id and .requester_id are ON DELETE CASCADE and
 * removing a row would take every modification that used it. Retiring one
 * has to keep working on the records that already reference it, which is why
 * there are two scopes rather than one.
 */
trait IsLookupOption
{
    /** Entries still on offer when filling in something new. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Entries on offer when editing an existing record: the active ones plus
     * whichever this record already uses, so a retired entry does not quietly
     * disappear from the form and change the saved value.
     */
    public function scopeActiveOr(Builder $query, int|string|null $keep): Builder
    {
        return $query->where(function (Builder $q) use ($keep) {
            $q->where('is_active', true);

            if ($keep !== null && $keep !== '') {
                $q->orWhere($this->getKeyName(), $keep);
            }
        });
    }
}

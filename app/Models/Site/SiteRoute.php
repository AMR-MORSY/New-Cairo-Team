<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One parent -> child hop taken from a transmission planning sheet.
 *
 * Unlike a Cascade, the same child may appear under several parents; that is
 * the point of this table. See the create_site_routes_table migration.
 */
class SiteRoute extends Model
{
    protected $table = 'site_routes';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected function casts(): array
    {
        return ['level' => 'integer', 'sheet_row' => 'integer', 'path_names' => 'array'];
    }

    /** The child site, when it is one of ours; enterprise links have no row. */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'child_code', 'site_code');
    }

    public function parentSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'parent_code', 'site_code');
    }

    public function hubSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'hub_code', 'site_code');
    }
}

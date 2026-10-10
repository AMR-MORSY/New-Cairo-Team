<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

/**
 * What was last read out of a planning sheet, and from which version of it.
 */
class SheetImport extends Model
{
    protected $table = 'sheet_imports';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['imported_at' => 'datetime'];
    }

    /**
     * Size and modification time, which is all it takes to tell that a
     * workbook has been edited without opening it.
     */
    public static function fingerprint(string $path): ?string
    {
        if (! is_readable($path)) {
            return null;
        }

        clearstatcache(true, $path);

        return filesize($path) . ':' . filemtime($path);
    }

    public function matches(string $path): bool
    {
        return $this->fingerprint === self::fingerprint($path);
    }
}

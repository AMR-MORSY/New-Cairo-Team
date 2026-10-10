<?php

namespace App\Support\Sheets;

/**
 * The planners' cell fills, translated to classes once.
 *
 * The colouring is most of how the planning sheet reads, so wherever the
 * sheet is redrawn - the Sheet tab's grid, the routes card - it has to be
 * the same red, the same yellow, the same green. Keeping the match in one
 * place is what guarantees that.
 *
 * NOTE: these class names only exist in this file, so app/Support must stay
 * in the @source list in resources/css/app.css or Tailwind emits none of them.
 */
final class SheetFill
{
    /** Fills the sheet uses, in the order a legend should list them. */
    public const FILLS = ['red', 'yellow', 'green', null];

    public static function classes(?string $fill): string
    {
        return match ($fill) {
            'red' => 'bg-red-600 text-white',
            'yellow' => 'bg-yellow-300 text-black',
            'green' => 'bg-[#92D050] text-black',
            default => 'bg-base-100 text-base-content',
        };
    }

    public static function label(?string $fill): string
    {
        return $fill ?? 'none';
    }
}

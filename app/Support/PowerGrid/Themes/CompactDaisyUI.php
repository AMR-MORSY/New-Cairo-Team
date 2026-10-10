<?php

namespace App\Support\PowerGrid\Themes;

use PowerComponents\LivewirePowerGrid\Themes\DaisyUI;

/**
 * The daisyUI theme at a tighter density, for the wide grids.
 *
 * SiteModificationsTable carries a dozen columns; at the default row height
 * it scrolls horizontally on anything but a desktop. table-sm buys the width
 * back, and table-pin-rows keeps the header visible while scrolling.
 *
 * The body stays base-100 (white) rather than zebra-striped, with base-200
 * (light grey) on hover, so the row under the cursor is the only one marked.
 *
 * Opt a grid in with customThemeClass(); everything else keeps the global
 * theme from config/livewire-powergrid.php.
 */
final class CompactDaisyUI extends DaisyUI
{
    public function table(): array
    {
        return array_replace_recursive(parent::table(), [
            'layout' => [
                // no table-zebra: plain white body, grey only under the cursor
                'table' => 'table table-sm table-pin-rows bg-base-100',
            ],
            'body' => [
                'tr' => 'hover:bg-orange-100',

            ],

            'header' => [
                'thead' => 'text-white! !capitalize',
                'tr' => 'bg-orange-400',
                'th' => '',
                'thAction' => '',
            ],
        ]);
    }

    /**
     * The wrapper around every column title, from cols.blade.php.
     *
     * The parent puts `!text-base-content` here, which compiles to an
     * !important colour on an element *inside* the th - so `text-white` on
     * the thead never reaches the title. Only an equally important rule on
     * this same div wins.
     */
    public function cols(): array
    {
        return [
            'div' => 'select-none flex items-center gap-1 text-white!',
        ];
    }
}

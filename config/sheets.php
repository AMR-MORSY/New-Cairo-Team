<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Transmission planning sheets
    |--------------------------------------------------------------------------
    |
    | The planners keep these workbooks up to date by hand, so the app watches
    | them rather than waiting for someone to run an import. The scheduler
    | checks each one every minute; the check is a stat() call and only reads
    | the workbook when its size or modification time has changed.
    |
    | Each entry is  source name => absolute path to the .xlsx.
    | The source name keys the rows in `site_routes`, so one sheet can be
    | re-imported without touching the others.
    |
    */

    'routes' => [
        'new-cairo-mmuid' => env('SHEET_NEW_CAIRO', base_path('New Cairo MMUID.xlsx')),
    ],

    /*
    | Turn the watcher off (for example on a machine where the sheets are not
    | mounted) without removing the paths above.
    */
    'watch' => env('SHEET_WATCH', true),

];

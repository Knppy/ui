<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    |
    | Knppy UI uses Laravel's cache system to store the merged classes.
    | Here you can customize the cache store that Knppy UI uses.
    |
    */
    'cache_store' => env('UI_CACHE_STORE'),

    /*
    |--------------------------------------------------------------------------
    | twMerge
    |--------------------------------------------------------------------------
    |
    | TODO
    |
    */
    'twMerge' => [
        'classGroups' => [],
        'prefix' => env('UI_MERGE_PREFIX'),
        'theme' => [],
    ],
];

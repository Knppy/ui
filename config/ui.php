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
    | Registries
    |--------------------------------------------------------------------------
    |
    | Registries that ui:add can pull components from, keyed by namespace
    | (use them as @namespace/component). A value is either a URL template
    | containing {name}, or a path to a local directory of {name}.json files.
    | A "registries" key in components.json is merged over these.
    |
    */
    'registries' => [
        '@knppy' => env('UI_REGISTRY_URL', 'https://ui.knppy.com/r/{name}.json'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Registry
    |--------------------------------------------------------------------------
    |
    | The registry used when a component is referenced without a namespace.
    |
    */
    'default_registry' => '@knppy',

    /*
    |--------------------------------------------------------------------------
    | Bundled Registry Fallback
    |--------------------------------------------------------------------------
    |
    | When the remote @knppy registry is unavailable, fall back to the
    | registry bundled with this package. Other registries never fall back.
    |
    */
    'fallback' => env('UI_REGISTRY_FALLBACK', true),

    /*
    |--------------------------------------------------------------------------
    | twMerge
    |--------------------------------------------------------------------------
    |
    | Options passed to tailwind-merge-php: extra class groups, a Tailwind
    | prefix (UI_MERGE_PREFIX) and theme values, used when merging the
    | classes of components through Ui::classes() and twMerge().
    |
    */
    'twMerge' => [
        'classGroups' => [],
        'prefix' => env('UI_MERGE_PREFIX'),
        'theme' => [],
    ],
];

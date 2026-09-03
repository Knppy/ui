<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components/{component}', function (string $component) {
    abort_unless(
        preg_match('/^[a-z0-9-]+$/', $component) && is_dir(resource_path('views/examples/'.$component)), 404,
    );

    return view('docs.component', ['slug' => $component]);
})->name('docs.component');

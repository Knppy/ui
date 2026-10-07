<?php

declare(strict_types=1);

use Ui\Ui\Ui;

it('resolves the singleton', function () {
    expect(app(Ui::class))->toBeInstanceOf(Ui::class);
});

it('returns the same instance from the container', function () {
    expect(app(Ui::class))->toBe(app(Ui::class));
});

it('merges the package config', function () {
    expect(config('ui.placeholder'))->toBe('default');
});

it('registers the artisan command', function () {
    $this->artisan('ui:placeholder')
        ->expectsOutputToContain('Ui placeholder command executed.')
        ->assertSuccessful();
});

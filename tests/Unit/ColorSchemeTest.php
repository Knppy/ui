<?php

declare(strict_types=1);

use Knppy\Ui\ColorScheme;

it('has the expected cases', function () {
    expect(array_map(fn (ColorScheme $c) => $c->value, ColorScheme::cases()))
        ->toBe(['neutral', 'zinc', 'stone', 'gray']);
});

it('returns light and dark css variables', function (ColorScheme $scheme) {
    $css = $scheme->getAppCssVariables();

    expect($css)
        ->toContain(':root {')
        ->toContain('.dark {')
        ->toContain('--background:')
        ->toContain('--primary:');
})->with(ColorScheme::cases());

it('returns distinct css per scheme', function () {
    $css = array_map(fn (ColorScheme $c) => $c->getAppCssVariables(), ColorScheme::cases());

    expect(array_unique($css))->toHaveCount(4);
});

it('resolves from string', function () {
    expect(ColorScheme::tryFrom('zinc'))->toBe(ColorScheme::ZINC)
        ->and(ColorScheme::tryFrom('nope'))->toBeNull();
});

<?php

declare(strict_types=1);

use Knppy\Ui\Enums\ColorScheme;

test('neutral case has correct value', function (): void {
    expect(ColorScheme::NEUTRAL->value)->toBe('neutral');
});

test('can be created from string value', function (): void {
    expect(ColorScheme::from('neutral'))->toBe(ColorScheme::NEUTRAL);
});

test('getThemeVariables returns non-empty CSS string for neutral', function (): void {
    $css = ColorScheme::NEUTRAL->getThemeVariables();

    expect($css)
        ->toBeString()
        ->toContain('--background')
        ->toContain('--foreground')
        ->toContain('.dark');
});

<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

test('checkbox forwards wire:model to the alpine wrapper, not the native input', function (): void {
    $html = Blade::render('<x-ui.checkbox wire:model.live="agree" />');

    preg_match('/<span[^>]*>/', $html, $wrapper);
    preg_match('/<input[^>]*>/', $html, $input);

    expect($wrapper[0])
        ->toContain('x-data="uiCheckbox(false)"')
        ->toContain('wire:model.live="agree"')
        ->and($input[0])->not->toContain('wire:model');
});

test('switch forwards wire:model to the alpine wrapper, not the native input', function (): void {
    $html = Blade::render('<x-ui.switch wire:model="active" />');

    preg_match('/<label[^>]*>/', $html, $wrapper);
    preg_match('/<input[^>]*>/', $html, $input);

    expect($wrapper[0])
        ->toContain('x-data="uiSwitch(false)"')
        ->toContain('wire:model="active"')
        ->and($input[0])->not->toContain('wire:model');
});

test('slider forwards wire:model to the alpine wrapper, not the native range input', function (): void {
    $html = Blade::render('<x-ui.slider wire:model="volume" />');

    preg_match('/<label[^>]*>/', $html, $wrapper);
    preg_match('/<input[^>]*>/', $html, $input);

    expect($wrapper[0])
        ->toContain('wire:model="volume"')
        ->and($input[0])->not->toContain('wire:model');
});

test('input-otp forwards wire:model to the alpine wrapper, not the native input', function (): void {
    $html = Blade::render('<x-ui.input-otp wire:model="code" />');

    preg_match('/<div[^>]*>/', $html, $wrapper);
    preg_match('/<input[^>]*>/', $html, $input);

    expect($wrapper[0])
        ->toContain('x-data="uiInputOtp(\'\', 6)"')
        ->toContain('wire:model="code"')
        ->and($input[0])->not->toContain('wire:model');
});

test('command-dialog forwards wire:model to the dialog, not dialog-content', function (): void {
    $html = Blade::render('<x-ui.command-dialog wire:model="open" />');

    expect($html)->toContain('wire:model="open"');

    preg_match('/<div[^>]*data-slot="dialog-content"[^>]*>/', $html, $dialogContent);
    expect($dialogContent[0])->not->toContain('wire:model');
});

test('calendar accepts wire:model on its alpine wrapper', function (): void {
    $html = Blade::render('<x-ui.calendar wire:model="date" />');

    expect($html)
        ->toContain('data-slot="calendar"')
        ->toContain('wire:model="date"');
});

test('navigation-menu accepts wire:model on its alpine wrapper', function (): void {
    $html = Blade::render('<x-ui.navigation-menu wire:model="active" />');

    expect($html)
        ->toContain('data-slot="navigation-menu"')
        ->toContain('wire:model="active"');
});

test('select and combobox forward wire:model to their alpine wrapper', function (): void {
    $select = Blade::render('<x-ui.select wire:model="fruit" />');
    $combobox = Blade::render('<x-ui.combobox wire:model="fruit" />');

    expect($select)
        ->toContain('x-data="uiSelect(null, false)"')
        ->toContain('wire:model="fruit"')
        ->and($combobox)
        ->toContain('x-data="uiCombobox(null, false, false)"')
        ->toContain('wire:model="fruit"');
});

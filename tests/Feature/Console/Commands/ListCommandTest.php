<?php

declare(strict_types=1);

it('lists all components with a count', function () {
    $this->artisan('ui:list')
        ->expectsOutputToContain('components found')
        ->assertSuccessful();
});

it('shows a table with component names and dependencies', function () {
    $this->artisan('ui:list')
        ->expectsOutputToContain('Component name')
        ->expectsOutputToContain('button')
        ->assertSuccessful();
});

it('shows the add hint when listing all components', function () {
    $this->artisan('ui:list')
        ->expectsOutputToContain('php artisan ui:add')
        ->assertSuccessful();
});

it('shows details for a single known component', function () {
    $this->artisan('ui:list button')
        ->expectsOutputToContain('Component: button')
        ->expectsOutputToContain('Files:')
        ->expectsOutputToContain('Depends on:')
        ->expectsOutputToContain('Composer:')
        ->expectsOutputToContain('npm:')
        ->expectsOutputToContain('php artisan ui:add button')
        ->assertSuccessful();
});

it('shows the registry dependencies for a component that has them', function () {
    $this->artisan('ui:list button-group')
        ->expectsOutputToContain('button')
        ->assertSuccessful();
});

it('fails with an error for an unknown component', function () {
    $this->artisan('ui:list unknown-component')
        ->expectsOutputToContain('Unknown component: unknown-component')
        ->assertFailed();
});

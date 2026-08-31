<?php

declare(strict_types=1);

use Knppy\Ui\ClassBuilder;
use Knppy\Ui\Ui;

test('classes with no argument returns a ClassBuilder', function (): void {
    expect(app(Ui::class)->classes())->toBeInstanceOf(ClassBuilder::class);
});

test('classes with null returns a ClassBuilder', function (): void {
    expect(app(Ui::class)->classes(null))->toBeInstanceOf(ClassBuilder::class);
});

test('classes with a string returns a ClassBuilder with the class added', function (): void {
    $builder = app(Ui::class)->classes('flex');

    expect($builder)->toBeInstanceOf(ClassBuilder::class);
    expect($builder)->not->toBe(app(ClassBuilder::class));
});

test('classes with an array returns a ClassBuilder with the classes added', function (): void {
    $builder = app(Ui::class)->classes(['flex' => true, 'hidden' => false]);

    expect($builder)->toBeInstanceOf(ClassBuilder::class);
    expect($builder)->not->toBe(app(ClassBuilder::class));
});

test('classes with no argument returns the singleton ClassBuilder directly', function (): void {
    expect(app(Ui::class)->classes())->toBe(app(ClassBuilder::class));
});

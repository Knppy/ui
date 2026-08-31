<?php

declare(strict_types=1);

use Knppy\Ui\ClassBuilder;
use TailwindMerge\Support\Config;

/**
 * Build a ClassBuilder with the full default TailwindMerge config so that
 * twMerge() and __toString() can be exercised without fatal errors.
 */
function builder(): ClassBuilder
{
    return new ClassBuilder(Config::getMergedConfig());
}

test('can be instantiated with no arguments', function (): void {
    expect(new ClassBuilder)->toBeInstanceOf(ClassBuilder::class);
});

test('can be resolved from the container', function (): void {
    expect(app(ClassBuilder::class))->toBeInstanceOf(ClassBuilder::class);
});

test('container binding is a singleton', function (): void {
    expect(app(ClassBuilder::class))->toBe(app(ClassBuilder::class));
});

test('add returns a new clone, not the same instance', function (): void {
    $new = builder()->add('flex');

    expect($new)->toBeInstanceOf(ClassBuilder::class);
    expect($new)->not->toBe(builder());
});

test('add accepts a string of classes', function (): void {
    $result = (string) builder()->add('flex items-center');

    expect($result)->toContain('flex')->toContain('items-center');
});

test('add accepts an associative array of conditional classes', function (): void {
    $result = (string) builder()->add(['flex' => true, 'hidden' => false]);

    expect($result)->toContain('flex')->not->toContain('hidden');
});

test('chained add calls accumulate classes', function (): void {
    $result = (string) builder()->add('flex')->add('items-center');

    expect($result)->toContain('flex')->toContain('items-center');
});

test('toString on a fresh builder returns empty string', function (): void {
    expect((string) builder())->toBe('');
});

test('twMerge deduplicates conflicting tailwind classes', function (): void {
    expect(builder()->twMerge('p-4', 'p-8'))->toBe('p-8');
});

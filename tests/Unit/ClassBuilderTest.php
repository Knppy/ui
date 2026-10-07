<?php

declare(strict_types=1);

use Ui\Ui\ClassBuilder;

it('is empty by default', function () {
    expect((string) new ClassBuilder)->toBe('');
});

it('joins added classes with a space', function () {
    $builder = (new ClassBuilder)->add('p-4')->add(['font-bold', 'text-sm']);

    expect((string) $builder)->toBe('p-4 font-bold text-sm');
});

it('supports conditional classes', function () {
    $builder = (new ClassBuilder)->add(['p-4' => true, 'hidden' => false]);

    expect((string) $builder)->toBe('p-4');
});

it('is immutable', function () {
    $original = (new ClassBuilder)->add('p-4');
    $extended = $original->add('m-2');

    expect((string) $original)->toBe('p-4')
        ->and((string) $extended)->toBe('p-4 m-2');
});

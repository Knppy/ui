<?php

declare(strict_types=1);

use Illuminate\View\ComponentAttributeBag;
use Knppy\Ui\ClassBuilder;
use TailwindMerge\TailwindMerge;

it('binds the class builder and tailwind merge as singletons', function () {
    expect(app(ClassBuilder::class))->toBe(app(ClassBuilder::class))
        ->and(app(TailwindMerge::class))->toBe(app(TailwindMerge::class));
});

it('merges conflicting classes through the twMerge attribute bag macro', function () {
    $bag = (new ComponentAttributeBag(['class' => 'p-8']))->twMerge('p-4', 'text-sm');

    expect($bag->get('class'))->toContain('p-8')
        ->and($bag->get('class'))->not->toContain('p-4');
});

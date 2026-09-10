<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

test('message scroller renders its interactive parts', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-ui.message-scroller>
            <x-ui.message-scroller-viewport>
                <x-ui.message-scroller-content>
                    <x-ui.message-scroller-item scrollAnchor>Message</x-ui.message-scroller-item>
                </x-ui.message-scroller-content>
            </x-ui.message-scroller-viewport>
            <x-ui.message-scroller-button />
        </x-ui.message-scroller>
    BLADE);

    expect($html)
        ->toContain('x-data="uiMessageScroller"')
        ->toContain('data-slot="message-scroller-viewport"')
        ->toContain('data-scroll-anchor')
        ->toContain('data-slot="message-scroller-button"')
        ->toContain('x-show="isButtonActive(\'end\')"')
        ->toContain('x-on:click="scrollToEdge(\'end\')"');
});

test('scroll area renders a native viewport and styled scrollbar', function (): void {
    $html = Blade::render('<x-ui.scroll-area class="h-40">Content</x-ui.scroll-area>');

    expect($html)
        ->toContain('x-data="uiScrollArea"')
        ->toContain('data-slot="scroll-area-viewport"')
        ->toContain('data-slot="scroll-area-scrollbar"')
        ->toContain('data-orientation="vertical"');
});

test('resizable renders accessible panels and handle', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-ui.resizable-panel-group orientation="vertical">
            <x-ui.resizable-panel defaultSize="40">One</x-ui.resizable-panel>
            <x-ui.resizable-handle withHandle />
            <x-ui.resizable-panel defaultSize="60">Two</x-ui.resizable-panel>
        </x-ui.resizable-panel-group>
    BLADE);

    expect($html)
        ->toContain('x-data="uiResizable(\'vertical\')"')
        ->toContain('data-default-size="40"')
        ->toContain('role="separator"')
        ->toContain('x-on:keydown="resizeWithKeyboard($event)"');
});

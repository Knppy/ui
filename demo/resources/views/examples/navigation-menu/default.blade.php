@php
    $navigationItems = [
        [
            'title' => 'Alert Dialog',
            'href' => 'components/alert-dialog',
            'description' => 'A modal dialog that interrupts the user with important content and expects a response.',
        ],
        [
            'title' => 'Hover Card',
            'href' => 'components/hover-card',
            'description' => 'For sighted users to preview content available behind a link.',
        ],
        [
            'title' => 'Progress',
            'href' => 'components/progress',
            'description' => 'Displays an indicator showing the completion progress of a task, typically displayed as a progress bar.',
        ],
        [
            'title' => 'Scroll-area',
            'href' => 'components/scroll-area',
            'description' => 'Visually or semantically separates content.',
        ],
        [
            'title' => 'Tabs',
            'href' => 'components/tabs',
            'description' => 'A set of layered sections of content—known as tab panels—that are displayed one at a time.',
        ],
        [
            'title' => 'Tooltip',
            'href' => 'components/tooltip',
            'description' => 'A popup that displays information related to an element when the element receives keyboard focus or the mouse hovers over it.',
        ],
    ];
@endphp
<x-ui.navigation-menu>
    <x-ui.navigation-menu-list>
        <x-ui.navigation-menu-item value="getting-started">
            <x-ui.navigation-menu-trigger>Getting started</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content>
                <ul class="w-96">
                    <li>
                        <x-ui.navigation-menu-link href="/docs" title="Introduction">
                            <div class="leading-none font-medium">Introduction</div>
                            <div class="text-muted-foreground line-clamp-2">
                                Re-usable components built with Tailwind CSS.
                            </div>
                        </x-ui.navigation-menu-link>
                    </li>
                    <li>
                        <x-ui.navigation-menu-link href="/docs/installation" title="Installation">
                            <div class="leading-none font-medium">Installation</div>
                            <div class="text-muted-foreground line-clamp-2">
                                How to install dependencies and structure your app.
                            </div>
                        </x-ui.navigation-menu-link>
                    </li>
                    <li>
                        <x-ui.navigation-menu-link href="components/typography" title="Typography">
                            <div class="leading-none font-medium">Typography</div>
                            <div class="text-muted-foreground line-clamp-2">
                                Styles for headings, paragraphs, lists...etc
                            </div>
                        </x-ui.navigation-menu-link>
                    </li>
                </ul>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item value="components" class="hidden md:flex">
            <x-ui.navigation-menu-trigger>Components</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content>
                <ul class="grid w-[400px] gap-2 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
                    @foreach ($navigationItems as $navigationItem)
                        <li>
                            <x-ui.navigation-menu-link
                                href="{{ $navigationItem['href'] }}"
                                title="{{ $navigationItem['title'] }}"
                            >
                                <div class="leading-none font-medium">{{ $navigationItem['title'] }}</div>
                                <div class="text-muted-foreground line-clamp-2">
                                    {{ $navigationItem['description'] }}
                                </div>
                            </x-ui.navigation-menu-link>
                        </li>
                    @endforeach
                </ul>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item value="with-icon">
            <x-ui.navigation-menu-trigger>With Icon</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content>
                <ul class="grid w-[200px]">
                    <li>
                        <x-ui.navigation-menu-link href="#" class="flex-row items-center gap-2">
                            <x-lucide-circle-alert class="size-4" />
                            Backlog
                        </x-ui.navigation-menu-link>
                        <x-ui.navigation-menu-link href="#" class="flex-row items-center gap-2">
                            <x-lucide-circle-dashed class="size-4" />
                            To Do
                        </x-ui.navigation-menu-link>
                        <x-ui.navigation-menu-link href="#" class="flex-row items-center gap-2">
                            <x-lucide-circle-check class="size-4" />
                            Done
                        </x-ui.navigation-menu-link>
                    </li>
                </ul>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item value="docs">
            <x-ui.navigation-menu-link href="/docs">Docs</x-ui.navigation-menu-link>
        </x-ui.navigation-menu-item>
    </x-ui.navigation-menu-list>
</x-ui.navigation-menu>

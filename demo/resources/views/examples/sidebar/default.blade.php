@php
    $navigation = [
        ['title' => 'Playground', 'icon' => 'terminal', 'active' => true, 'items' => ['History', 'Starred', 'Settings']],
        ['title' => 'Models', 'icon' => 'bot', 'items' => ['Genesis', 'Explorer', 'Quantum']],
        ['title' => 'Documentation', 'icon' => 'book', 'items' => ['Introduction', 'Get Started', 'Tutorials', 'Changelog']],
        ['title' => 'Settings', 'icon' => 'settings', 'items' => ['General', 'Team', 'Billing', 'Limits']],
    ];

    $projects = [
        ['name' => 'Design Engineering', 'icon' => 'frame'],
        ['name' => 'Sales & Marketing', 'icon' => 'chart'],
        ['name' => 'Travel', 'icon' => 'map'],
    ];
@endphp

<x-ui.sidebar-provider class="min-h-[400px] rounded border">
    <x-ui.sidebar collapsible="icon">
        <x-ui.sidebar-header>
            <x-ui.sidebar-menu>
                <x-ui.sidebar-menu-item>
                    <x-ui.dropdown-menu>
                        <x-ui.dropdown-menu-trigger>
                            <x-ui.sidebar-menu-button size="lg">
                                <span class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="16" height="12" x="4" y="8" rx="2" />
                                        <path d="M8 4h8M7 12h10" />
                                    </svg>
                                </span>
                                <span class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-medium">Acme Inc</span>
                                    <span class="truncate text-xs">Enterprise</span>
                                </span>
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto size-4"><path d="m7 15 5 5 5-5M7 9l5-5 5 5" /></svg>
                            </x-ui.sidebar-menu-button>
                        </x-ui.dropdown-menu-trigger>
                        <x-ui.dropdown-menu-content
                            align="start"
                            side="right"
                            sideOffset="4"
                            class="min-w-56 rounded-lg"
                        >
                            <x-ui.dropdown-menu-label class="text-muted-foreground text-xs">
                                Teams</x-ui.dropdown-menu-label>
                            <x-ui.dropdown-menu-item>
                                Acme Inc
                                <x-ui.dropdown-menu-shortcut>⌘1</x-ui.dropdown-menu-shortcut></x-ui.dropdown-menu-item>
                            <x-ui.dropdown-menu-item>
                                Acme Corp.
                                <x-ui.dropdown-menu-shortcut>⌘2</x-ui.dropdown-menu-shortcut></x-ui.dropdown-menu-item>
                            <x-ui.dropdown-menu-item>
                                Evil Corp.
                                <x-ui.dropdown-menu-shortcut>⌘3</x-ui.dropdown-menu-shortcut></x-ui.dropdown-menu-item>
                        </x-ui.dropdown-menu-content>
                    </x-ui.dropdown-menu>
                </x-ui.sidebar-menu-item>
            </x-ui.sidebar-menu>
        </x-ui.sidebar-header>

        <x-ui.sidebar-content>
            <x-ui.sidebar-group>
                <x-ui.sidebar-group-label>Platform</x-ui.sidebar-group-label>
                <x-ui.sidebar-menu>
                    @foreach ($navigation as $item)
                        <x-ui.collapsible :open="$item['active'] ?? false" class="group/collapsible">
                            <x-ui.sidebar-menu-item>
                                <x-ui.collapsible-trigger>
                                    <x-ui.sidebar-menu-button>
                                        @switch ($item['icon'])
                                            @case ('terminal')
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="18" height="14" x="3" y="5" rx="2" />
                                                    <path d="m7 9 3 3-3 3M13 15h4" />
                                                </svg>
                                                @break
                                            @case ('bot')
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="16" height="12" x="4" y="8" rx="2" />
                                                    <path d="M9 12h.01M15 12h.01M9 16h6M12 4v4M8 4h8" />
                                                </svg>
                                                @break
                                            @case ('book')
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 5.5A2.5 2.5 0 0 1 4.5 3H11v18H4.5A2.5 2.5 0 0 0 2 23zM22 5.5A2.5 2.5 0 0 0 19.5 3H13v18h6.5A2.5 2.5 0 0 1 22 23z" /></svg>
                                                @break
                                            @case ('settings')
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 18h16M8 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM20 12H4M20 18a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM14 12a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z" /></svg>
                                                @break
                                        @endswitch
                                        <span>{{ $item['title'] }}</span>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto size-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"><path d="m9 18 6-6-6-6" /></svg>
                                    </x-ui.sidebar-menu-button>
                                </x-ui.collapsible-trigger>
                                <x-ui.collapsible-content>
                                    <x-ui.sidebar-menu-sub>
                                        @foreach ($item['items'] as $subItem)
                                            <x-ui.sidebar-menu-sub-item>
                                                <x-ui.sidebar-menu-sub-button href="#">
                                                    <span>{{ $subItem }}</span>
                                                </x-ui.sidebar-menu-sub-button>
                                            </x-ui.sidebar-menu-sub-item>
                                        @endforeach
                                    </x-ui.sidebar-menu-sub>
                                </x-ui.collapsible-content>
                            </x-ui.sidebar-menu-item>
                        </x-ui.collapsible>
                    @endforeach
                </x-ui.sidebar-menu>
            </x-ui.sidebar-group>

            <x-ui.sidebar-group class="group-data-[collapsible=icon]:hidden">
                <x-ui.sidebar-group-label>Projects</x-ui.sidebar-group-label>
                <x-ui.sidebar-menu>
                    @foreach ($projects as $project)
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button is="a" href="#">
                                @switch ($project['icon'])
                                    @case ('frame')
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect width="18" height="18" x="3" y="3" rx="2" />
                                            <path d="M3 9h18M9 21V9" />
                                        </svg>
                                        @break
                                    @case ('chart')
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 12a9 9 0 1 1-9-9v9z" />
                                            <path d="M12 3a9 9 0 0 1 9 9h-9z" />
                                        </svg>
                                        @break
                                    @case ('map')
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15" /></svg>
                                        @break
                                @endswitch
                                <span>{{ $project['name'] }}</span>
                            </x-ui.sidebar-menu-button>
                            <x-ui.dropdown-menu>
                                <x-ui.dropdown-menu-trigger>
                                    <x-ui.sidebar-menu-action showOnHover>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                            <circle cx="5" cy="12" r="1.5" />
                                            <circle cx="12" cy="12" r="1.5" />
                                            <circle cx="19" cy="12" r="1.5" />
                                        </svg>
                                        <span class="sr-only">More</span>
                                    </x-ui.sidebar-menu-action>
                                </x-ui.dropdown-menu-trigger>
                                <x-ui.dropdown-menu-content align="start" side="right" class="w-48 rounded-lg">
                                    <x-ui.dropdown-menu-item>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h5l2 2h11v10H3z" /></svg>
                                        <span>View Project</span>
                                    </x-ui.dropdown-menu-item>
                                    <x-ui.dropdown-menu-item>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 8 5 4-5 4v-3H4v-2h11z" /></svg>
                                        <span>Share Project</span>
                                    </x-ui.dropdown-menu-item>
                                    <x-ui.dropdown-menu-separator />
                                    <x-ui.dropdown-menu-item>
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" /></svg>
                                        <span>Delete Project</span>
                                    </x-ui.dropdown-menu-item>
                                </x-ui.dropdown-menu-content>
                            </x-ui.dropdown-menu>
                        </x-ui.sidebar-menu-item>
                    @endforeach
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button class="text-sidebar-foreground/70">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <circle cx="5" cy="12" r="1.5" />
                                <circle cx="12" cy="12" r="1.5" />
                                <circle cx="19" cy="12" r="1.5" />
                            </svg>
                            <span>More</span>
                        </x-ui.sidebar-menu-button>
                    </x-ui.sidebar-menu-item>
                </x-ui.sidebar-menu>
            </x-ui.sidebar-group>
        </x-ui.sidebar-content>

        <x-ui.sidebar-footer>
            <x-ui.sidebar-menu>
                <x-ui.sidebar-menu-item>
                    <x-ui.dropdown-menu>
                        <x-ui.dropdown-menu-trigger>
                            <x-ui.sidebar-menu-button size="lg">
                                <x-ui.avatar class="size-8 rounded-lg">
                                    <x-ui.avatar-image src="https://github.com/shadcn.png" alt="shadcn" />
                                    <x-ui.avatar-fallback class="rounded-lg">CN</x-ui.avatar-fallback>
                                </x-ui.avatar>
                                <span class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-medium">shadcn</span>
                                    <span class="truncate text-xs">m@example.com</span>
                                </span>
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto size-4"><path d="m7 15 5 5 5-5M7 9l5-5 5 5" /></svg>
                            </x-ui.sidebar-menu-button>
                        </x-ui.dropdown-menu-trigger>
                        <x-ui.dropdown-menu-content align="end" side="right" sideOffset="4" class="min-w-56 rounded-lg">
                            <x-ui.dropdown-menu-label>
                                shadcn<br /><span class="text-muted-foreground text-xs font-normal"
                                    >m@example.com</span
                                ></x-ui.dropdown-menu-label>
                            <x-ui.dropdown-menu-separator />
                            <x-ui.dropdown-menu-item>Account</x-ui.dropdown-menu-item>
                            <x-ui.dropdown-menu-item>Billing</x-ui.dropdown-menu-item>
                            <x-ui.dropdown-menu-item>Notifications</x-ui.dropdown-menu-item>
                            <x-ui.dropdown-menu-separator />
                            <x-ui.dropdown-menu-item>Log out</x-ui.dropdown-menu-item>
                        </x-ui.dropdown-menu-content>
                    </x-ui.dropdown-menu>
                </x-ui.sidebar-menu-item>
            </x-ui.sidebar-menu>
        </x-ui.sidebar-footer>
        <x-ui.sidebar-rail />
    </x-ui.sidebar>

    <x-ui.sidebar-inset>
        <header class="flex h-16 shrink-0 items-center gap-2 px-4">
            <x-ui.sidebar-trigger />
        </header>
    </x-ui.sidebar-inset>
</x-ui.sidebar-provider>

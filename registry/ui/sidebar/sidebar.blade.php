@props([
    'side' => 'left',
    'variant' => 'sidebar',
    'collapsible' => 'offcanvas',
])

@if ($collapsible === 'none')
    <aside
        data-slot="sidebar"
        data-sidebar="sidebar"
        data-side="{{ $side }}"
        data-variant="{{ $variant }}"
        data-collapsible="none"
        {{ $attributes->twMerge(['class' => 'flex h-svh w-(--sidebar-width) flex-col bg-sidebar text-sidebar-foreground']) }}
    >
        {{ $slot }}
    </aside>
@else
    <div
        class="group peer text-sidebar-foreground"
        x-bind:data-state="state"
        x-bind:data-mobile="isMobile || null"
        x-bind:data-collapsible="! isMobile && state === 'collapsed' ? @js($collapsible) : ''"
        data-side="{{ $side }}"
        data-variant="{{ $variant }}"
        data-collapse-mode="{{ $collapsible }}"
        data-slot="sidebar"
    >
        <div
            data-slot="sidebar-gap"
            class="relative hidden w-(--sidebar-width) bg-transparent transition-[width] duration-200 ease-linear group-data-[collapsible=icon]:w-(--sidebar-width-icon) group-data-[collapsible=offcanvas]:w-0 group-data-[variant=floating]:group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+1rem)] group-data-[variant=inset]:group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+1rem)] md:block"
        ></div>
        <div
            x-show="! isMobile || openMobile"
            x-on:click.self="closeMobile()"
            x-transition.opacity
            class="absolute inset-0 z-40 bg-black/50 md:hidden"
        ></div>
        <aside
            x-bind:aria-hidden="isMobile && ! openMobile"
            x-bind:style="sidebarStyle(@js($side), @js($collapsible))"
            data-slot="sidebar-container"
            {{ $attributes->twMerge(['class' => 'absolute inset-y-0 z-50 flex h-full w-(--sidebar-width-mobile) transition-[left,right,width,transform] duration-200 ease-linear md:z-10 md:w-(--sidebar-width) group-data-[side=left]:left-0 group-data-[side=right]:right-0 md:group-data-[collapsible=icon]:w-(--sidebar-width-icon) group-data-[variant=floating]:p-2 group-data-[variant=inset]:p-2']) }}
            x-bind:data-mobile-open="openMobile || null"
        >
            <div
                data-slot="sidebar-inner"
                data-sidebar="sidebar"
                class="bg-sidebar group-data-[variant=floating]:border-sidebar-border group-data-[variant=inset]:border-sidebar-border flex h-full w-full flex-col group-data-[side=left]:border-r group-data-[side=right]:border-l group-data-[variant=floating]:rounded-lg group-data-[variant=floating]:border group-data-[variant=floating]:shadow-sm group-data-[variant=inset]:rounded-lg group-data-[variant=inset]:border group-data-[variant=inset]:shadow-sm"
            >
                <div class="sr-only">
                    <h2>Sidebar</h2>
                    <p>Displays the mobile sidebar.</p>
                </div>
                {{ $slot }}
            </div>
        </aside>
    </div>
@endif

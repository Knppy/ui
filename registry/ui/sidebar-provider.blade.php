@props(['defaultOpen' => true, 'mobileBreakpoint' => '767px'])

@php
    $mobileQuery = '(max-width: '.(is_numeric($mobileBreakpoint) ? $mobileBreakpoint.'px' : $mobileBreakpoint).')';
    $style = rtrim('--sidebar-width: calc(var(--spacing) * 64); --sidebar-width-icon: calc(var(--spacing) * 12); '.$attributes->get('style', ''));
@endphp

<div
    data-slot="sidebar-provider"
    x-data="{
        open: {{ $defaultOpen ? 'true' : 'false' }},
        openMobile: false,
        isMobile: false,
        collapsed: false,
        toggle() { this.isMobile ? (this.openMobile = ! this.openMobile) : (this.open = ! this.open) },
        setOpen(value) {
            this.open = value;
            document.cookie = 'sidebar_state=' + value + '; path=/; max-age=' + (60 * 60 * 24 * 7);
        },
        init() {
            const mq = window.matchMedia(@js($mobileQuery));
            this.isMobile = mq.matches;
            mq.addEventListener('change', e => this.isMobile = e.matches);
            window.addEventListener('keydown', e => {
                if (e.key === 'b' && (e.metaKey || e.ctrlKey)) {
                    e.preventDefault();
                    this.toggle();
                }
            });
        }
    }"
    x-effect="
        collapsed = ! isMobile && ! open;
        if (! isMobile) {
            document.cookie = 'sidebar_state=' + open + '; path=/; max-age=' + 60 * 60 * 24 * 7;
        }
    "
    style="{{ $style }}"
    {{ $attributes->except('style')->twMerge('group/sidebar-wrapper flex min-h-svh w-full has-data-[variant=inset]:bg-sidebar') }}
>
    {{ $slot }}
</div>

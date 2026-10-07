@props([
    'position' => 'bottom-right',
    'closeButton' => false,
    'expand' => false,
    'visibleToasts' => 3,
])

<section
    aria-label="Notifications"
    data-slot="sonner"
    data-position="{{ $position }}"
    x-data="uiSonner(@js($position), @js($expand), @js($visibleToasts))"
    x-on:mouseenter="
        expanded = true;
        $store.toast.pause();
    "
    x-on:mouseleave="expanded = @js($expand); $store.toast.resume()"
    {{ $attributes->twMerge(['class' => 'pointer-events-none fixed inset-x-0 top-0 z-[100] p-4 data-[position^=bottom]:top-auto data-[position^=bottom]:bottom-0 sm:inset-x-auto sm:w-[388px] data-[position$=left]:left-0 data-[position$=right]:right-0 data-[position$=center]:left-1/2 data-[position$=center]:-translate-x-1/2']) }}
>
    <ol
        class="pointer-events-auto relative w-full transition-[height] duration-300 ease-out"
        x-bind:style="viewportStyle()"
    >
        <template x-for="(toast, index) in toasts" :key="toast.id">
            <li
                x-init="registerToast(toast, $el)"
                x-bind:style="toastStyle(index)"
                x-bind:data-type="toast.type"
                x-bind:role="toast.type === 'error' ? 'alert' : 'status'"
                class="bg-popover text-popover-foreground pointer-events-auto absolute inset-x-0 flex min-h-19 origin-bottom items-center gap-3 rounded-xl border p-4 shadow-lg transition-[bottom,top,transform,opacity] duration-300 ease-out"
            >
                <div class="grid min-w-0 flex-1 gap-1">
                    <div data-slot="sonner-title" class="text-sm font-semibold" x-text="toast.title"></div>
                    <div
                        data-slot="sonner-description"
                        class="text-muted-foreground text-sm"
                        x-show="toast.description"
                        x-text="toast.description"
                    ></div>
                </div>
                <button
                    type="button"
                    data-slot="sonner-action"
                    x-show="toast.action"
                    x-on:click="runAction(toast)"
                    x-text="toast.action?.label"
                    class="bg-background hover:bg-muted focus-visible:ring-ring shrink-0 rounded-lg border px-3 py-2 text-sm font-medium shadow-xs focus-visible:ring-2 focus-visible:outline-none"
                ></button>
                @if ($closeButton)
                    <button
                        type="button"
                        data-slot="sonner-close"
                        x-on:click="$store.toast.dismiss(toast.id)"
                        class="text-muted-foreground focus-visible:ring-ring shrink-0 rounded-md p-1 opacity-70 transition-opacity hover:opacity-100 focus-visible:ring-2 focus-visible:outline-none"
                    >
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5"><path d="M18 6 6 18M6 6l12 12" /></svg>
                        <span class="sr-only">Close notification</span>
                    </button>
                @endif
            </li>
        </template>
    </ol>
</section>

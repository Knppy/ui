<x-ui.collapsible class="flex w-[350px] flex-col gap-2">
    <div class="flex items-center justify-between gap-4 px-4">
        <h4 class="text-sm font-semibold">Order #4189</h4>
        <x-ui.collapsible-trigger>
            <x-ui.button variant="ghost" size="icon" class="size-8">
                <x-lucide-chevrons-up-down /><span class="sr-only">Toggle details</span></x-ui.button>
        </x-ui.collapsible-trigger>
    </div>
    <div class="flex items-center justify-between rounded-md border px-4 py-2 text-sm">
        <span class="text-muted-foreground">Status</span>
        <span class="font-medium">Shipped</span>
    </div>
    <x-ui.collapsible-content class="flex flex-col gap-2">
        <div class="rounded-md border px-4 py-2 text-sm">
            <p class="font-medium">Shipping address</p>
            <p class="text-muted-foreground">100 Market St, San Francisco</p>
        </div>
        <div class="rounded-md border px-4 py-2 text-sm">
            <p class="font-medium">Items</p>
            <p class="text-muted-foreground">2x Studio Headphones</p>
        </div>
    </x-ui.collapsible-content>
</x-ui.collapsible>

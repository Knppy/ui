@php
    $deliveryTimes = [
        [
            'value' => 'asap',
            'id' => 'delivery-asap',
            'label' => 'Standard delivery',
            'description' => '25–35 min · Driver assigned now',
            'badge' => 'Fastest',
        ],
        [
            'value' => '5-00',
            'id' => 'delivery-5-00',
            'label' => '5:00 PM – 5:15 PM',
            'description' => 'Prep starts at 4:45 PM',
        ],
        [
            'value' => '5-30',
            'id' => 'delivery-5-30',
            'label' => '5:30 PM – 5:45 PM',
            'description' => "Good if you're heading home",
        ],
        [
            'value' => '6-00',
            'id' => 'delivery-6-00',
            'label' => '6:00 PM – 6:15 PM',
            'description' => 'Most popular · High demand',
        ],
        [
            'value' => '6-30',
            'id' => 'delivery-6-30',
            'label' => '6:30 PM – 6:45 PM',
            'description' => 'Last slot before kitchen closes',
        ],
    ];
@endphp

<x-ui.drawer>
    <x-ui.drawer-trigger><x-ui.button variant="secondary">Open Drawer</x-ui.button></x-ui.drawer-trigger>
    <x-ui.drawer-content>
        <x-ui.drawer-header>
            <x-ui.drawer-title>Pick a delivery time</x-ui.drawer-title>
            <x-ui.drawer-description> We&apos;ll prepare your order as soon as possible. </x-ui.drawer-description>
        </x-ui.drawer-header>
        <div class="scroll-fade flex-1 overflow-y-auto p-4">
            <x-ui.radio-group value={deliveryTime} onValueChange={setDeliveryTime} class="gap-2">
                @foreach ($deliveryTimes as $time)
                    <x-ui.field-label for="{{ $time['id'] }}">
                        <x-ui.field orientation="horizontal">
                            <x-ui.field-content>
                                <x-ui.field-title class="flex items-center gap-2">
                                    {{ $time['label'] }}
                                    @if (isset($time['badge']))
                                        <x-ui.badge variant="secondary">{{ $time['badge'] }}</x-ui.badge>
                                    @endif
                                </x-ui.field-title>
                                <x-ui.field-description>{{ $time['description'] }}</x-ui.field-description>
                            </x-ui.field-content>
                            <x-ui.radio-group-item value="{{ $time['value'] }}" id="{{ $time['id'] }}" />
                        </x-ui.field>
                    </x-ui.field-label>
                @endforeach
            </x-ui.radio-group>
        </div>
        <x-ui.drawer-footer>
            <x-ui.button class="h-[34px]"> Confirm Delivery Time </x-ui.button>
            <x-ui.drawer-close><x-ui.button variant="outline">Cancel</x-ui.button></x-ui.drawer-close>
        </x-ui.drawer-footer>
    </x-ui.drawer-content>
</x-ui.drawer>

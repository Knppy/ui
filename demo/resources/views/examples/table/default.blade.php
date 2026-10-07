@php
    $invoices = [
        [
            'invoice' => 'INV001',
            'paymentStatus' => 'Paid',
            'totalAmount' => '$250.00',
            'paymentMethod' => 'Credit Card',
        ],
        [
            'invoice' => 'INV002',
            'paymentStatus' => 'Pending',
            'totalAmount' => '$150.00',
            'paymentMethod' => 'PayPal',
        ],
        [
            'invoice' => 'INV003',
            'paymentStatus' => 'Unpaid',
            'totalAmount' => '$350.00',
            'paymentMethod' => 'Bank Transfer',
        ],
        [
            'invoice' => 'INV004',
            'paymentStatus' => 'Paid',
            'totalAmount' => '$450.00',
            'paymentMethod' => 'Credit Card',
        ],
        [
            'invoice' => 'INV005',
            'paymentStatus' => 'Paid',
            'totalAmount' => '$550.00',
            'paymentMethod' => 'PayPal',
        ],
        [
            'invoice' => 'INV006',
            'paymentStatus' => 'Pending',
            'totalAmount' => '$200.00',
            'paymentMethod' => 'Bank Transfer',
        ],
        [
            'invoice' => 'INV007',
            'paymentStatus' => 'Unpaid',
            'totalAmount' => '$300.00',
            'paymentMethod' => 'Credit Card',
        ],
    ];
@endphp

<x-ui.table>
    <x-ui.table-caption>A list of your recent invoices.</x-ui.table-caption>
    <x-ui.table-header>
        <x-ui.table-row>
            <x-ui.table-head class="w-[100px]">Invoice</x-ui.table-head>
            <x-ui.table-head>Status</x-ui.table-head>
            <x-ui.table-head>Method</x-ui.table-head>
            <x-ui.table-head class="text-right">Amount</x-ui.table-head>
        </x-ui.table-row>
    </x-ui.table-header>
    <x-ui.table-body>
        @foreach ($invoices as $invoice)
            <x-ui.table-row key="{{ $invoice['invoice'] }}">
                <x-ui.table-cell class="font-medium">{{ $invoice['invoice'] }}</x-ui.table-cell>
                <x-ui.table-cell>{{ $invoice['paymentStatus'] }}</x-ui.table-cell>
                <x-ui.table-cell>{{ $invoice['paymentMethod'] }}</x-ui.table-cell>
                <x-ui.table-cell class="text-right">{{ $invoice['totalAmount'] }}</x-ui.table-cell>
            </x-ui.table-row>
        @endforeach
    </x-ui.table-body>
    <x-ui.table-footer>
        <x-ui.table-row>
            <x-ui.table-cell colspan="3">Total</x-ui.table-cell>
            <x-ui.table-cell class="text-right">$2,500.00</x-ui.table-cell>
        </x-ui.table-row>
    </x-ui.table-footer>
</x-ui.table>

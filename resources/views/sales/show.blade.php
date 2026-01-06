<x-dashboard-layout>
    <x-slot:title>Transaction Details</x-slot:title>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            /* Hide non-receipt elements */
            nav, aside, header, footer, .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="max-w-3xl mx-auto" id="print-area">
        <div class="mb-8 flex justify-between items-center no-print">
            <div>
                <a href="{{ route('sales.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Sales
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Transaction #{{ is_object($sale) ? $sale->id : $sale['id'] }}</h1>
            </div>
            <div>
                <x-button variant="outline" type="button" onclick="window.print()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Receipt
                </x-button>
            </div>
        </div>

        <x-card class="print:shadow-none print:border-none">
            <!-- Header Info -->
            <div class="grid grid-cols-2 gap-6 mb-8 border-b border-gray-100 pb-8">
                <div>
                    <h2 class="text-2xl font-bold text-primary-800 font-display mb-2 print:text-black">DAZO</h2>
                    <p class="font-semibold text-gray-900">{{ is_object($sale) ? (is_object($sale->store) ? $sale->store->name : $sale->store['name']) : $sale['store']['name'] }}</p>
                    <p class="text-sm text-gray-500">{{ is_object($sale) ? (is_object($sale->store) ? ($sale->store->address ?? '') : ($sale->store['address'] ?? '')) : ($sale['store']['address'] ?? '') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">Transaction Date</p>
                    <p class="font-semibold text-gray-900">{{ is_object($sale) ? (is_object($sale->transaction_date) ? $sale->transaction_date->format('d F Y, H:i') : \Carbon\Carbon::parse($sale->transaction_date)->format('d F Y, H:i')) : \Carbon\Carbon::parse($sale['transaction_date'])->format('d F Y, H:i') }}</p>
                    <p class="text-sm text-gray-500 mt-2">Cashier: {{ is_object($sale) ? (is_object($sale->cashier) ? $sale->cashier->name : $sale->cashier['name']) : $sale['cashier']['name'] }}</p>
                    <p class="text-sm text-gray-500">ID: #{{ is_object($sale) ? $sale->id : $sale['id'] }}</p>
                </div>
            </div>

            <!-- Items -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Items Purchased</h3>
                <div class="bg-gray-50 rounded-lg p-4 print:bg-white print:p-0">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Product</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Price</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Qty</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach((is_object($sale) ? $sale->items : $sale['items']) as $item)
                            <tr>
                                <td class="py-3 text-sm text-gray-900">{{ is_object($item) ? (is_object($item->product) ? $item->product->name : $item->product['name']) : $item['product']['name'] }}</td>
                                <td class="py-3 text-sm text-gray-500 text-right">Rp {{ number_format(is_object($item) ? $item->unit_price : $item['unit_price'], 0, ',', '.') }}</td>
                                <td class="py-3 text-sm text-gray-500 text-right">{{ is_object($item) ? $item->quantity : $item['quantity'] }}</td>
                                <td class="py-3 text-sm font-medium text-gray-900 text-right">Rp {{ number_format(is_object($item) ? $item->subtotal : $item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t border-gray-100 pt-6">
                <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                    <span>Total Amount</span>
                    <span class="text-green-600 print:text-black">Rp {{ number_format(is_object($sale) ? $sale->total_amount : $sale['total_amount'], 0, ',', '.') }}</span>
                </div>
                 <div class="mt-4 flex justify-between items-center text-sm">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-900 uppercase bg-gray-100 px-2 py-1 rounded print:bg-transparent print:p-0">{{ is_object($sale) ? ($sale->payment_method ?? 'Cash') : ($sale['payment_method'] ?? 'Cash') }}</span>
                </div>
            </div>
            
            <!-- Print Footer -->
            <div class="hidden print:block mt-8 text-center text-xs text-gray-500">
                <p>Thank you for your purchase!</p>
                <p class="mt-1">Powered by Dazo Store Management</p>
            </div>
        </x-card>
    </div>
</x-dashboard-layout>

@props(['product', 'transactions'])
<section>
    <div class="grid grid-cols-2 gap-5">
        <x-metric-box title="Total Views" border-color="border-gray-400">{{ $product->views }}</x-metric-box>
        <x-metric-box title="Total Sales (Qty)"
            border-color="border-gray-400">{{ $product->productType->sum('total_sales') }}</x-metric-box>
        <x-metric-box title="Total SKU (Unique)"
            border-color="border-gray-400">{{ $product->productType->unique('sku')->count() }}</x-metric-box>
        <x-metric-box title="Total Stock"
            border-color="border-gray-400">{{ $product->productType->sum('stock') }}</x-metric-box>
    </div>
</section>

<section>
    <!-- Latest Transactions -->
    <section>
        <h2 class="text-xl font-semibold py-5">Latest Completed Transactions</h2>
        <div class="flex flex-col gap-5">
            @if ($transactions->count() > 0)
                <table class="divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaction ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Value
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transactions->take(10) as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $qty = $transaction->transactionItems->sum('quantity');
                                    @endphp
                                    Rp{{ number_format($transaction->transactionItems->whereIn('product_type_id', $product->productType->pluck('id'))->sum('subtotal'), 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->transactionItems->whereIn('product_type_id', $product->productType->pluck('id'))->sum('quantity') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a class="flex flex-row items-center gap-2 cursor-pointer"
                                        href="{{ route('admin.transaction-management.view-transaction-details', $transaction->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                        See Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-red-500">No transactions found</p>
            @endif
        </div>
    </section>
</section>

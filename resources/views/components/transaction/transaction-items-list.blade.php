@props(['transactionItems'])
<div class="gap-5 border border-gray-300 px-5 rounded-xl">
    <table class="table-fixed divide-y w-[50vw]">
        <thead class="">
            <tr class="">
                <th class="text-start font-light py-5 w-[30rem]">Product</th>
                <th class="text-start font-light py-5">Price</th>
                <th class="text-start font-light py-5">Quantity</th>
                <th class="text-end font-light py-5">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach ($transaction->transactionItems as $transactionItem)
                @php
                    $discountedPrice = 0;
                    $discount_percentage = $transactionItem->productType->discount_percentage;
                    $discount_fixed = $transactionItem->productType->discount_fixed;
                    if ($discount_percentage > 0) {
                        $amount = $transactionItem->productType->price * ($discount_percentage / 100);
                        if ($discount_fixed > 0) {
                            $discountedPrice = $transactionItem->productType->price - min($amount, $discount_fixed);
                        } else {
                            $discountedPrice = $transactionItem->productType->price - $amount;
                        }
                    } elseif ($discount_fixed > 0) {
                        $discountedPrice = $transactionItem->productType->price - $discount_fixed;
                    }
                @endphp
                <tr>
                    <td class="flex items-center gap-5 text-lg py-3">
                        <span>
                            @if ($transactionItem->productType->getMedia('*')->count() > 0)
                                <img src="{{ $transactionItem->productType->getMedia('*')->first()->preview_url }}" alt="Product Image"
                                    class="w-24 h-24 object-scale-down">        
                            @else
                                <span class="w-24 h-24 inline-block bg-gray-500"></span>
                            @endif
                        </span>
                        <div>
                            <span class="font-light">
                                {{ $transactionItem->productType->product->name }}
                            </span>
                            <span class="font-light">-</span>
                            <span class="font-light">
                                {{ $transactionItem->productType->name }}
                            </span>
                        </div>
                    </td>
                    <td class="py-3">
                        @if ($discountedPrice > 0)
                            <span
                                class="line-through text-gray-400">Rp{{ number_format($transactionItem->productType->price, 0, ',', '.') }}</span>
                            <span>Rp{{ number_format($discountedPrice, 0, ',', '.') }}</span>
                        @else
                            <span>Rp{{ number_format($transactionItem->productType->price, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="py-3">
                        <span class="border border-gray-300 rounded-2xl px-4 py-1">
                            {{ $transactionItem->quantity }}
                        </span>
                    </td>
                    <td class="text-lg py-3 text-end font-semibold">
                        <span>Rp{{ number_format($transactionItem->subtotal, 0, ',', '.') }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

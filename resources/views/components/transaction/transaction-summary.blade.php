@props(['transaction', 'transactionItems'])
<div class="gap-5 border border-gray-300 p-5 rounded-xl">
    <h1 class="text-xl font-semibold">Summary</h1>
    <h2 class="text-lg font-semibold">Item Price</h2>

    <section class="flex flex-col gap-3 py-3 border-b border-gray-300">
        @foreach ($transactionItems as $item)
            <div class="flex flex-row gap-5 w-full justify-between">
                <span>
                    {{ $item->productType->product->name }} (x{{ $item->quantity }})
                </span>

                <span class="text-end">
                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                </span>
            </div>
        @endforeach
    </section>

    <section class="flex flex-col gap-3 py-3 border-b border-gray-300">
        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Subtotal</strong>
            </span>
            <span class="text-end">
                <strong>Rp{{ number_format($transaction->subtotal, 0, ',', '.') }}</strong>
            </span>
        </div>
        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Shipping Fee</strong>
            </span>
            <span class="text-end">
                <strong>Rp{{ number_format($transaction->delivery_fee, 0, ',', '.') }}</strong>
            </span>
        </div>

        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Voucher</strong>
            </span>
            @if (isset($transaction->voucher))
                <span class="text-end">
                    <strong>{{ $transaction->voucher->code }}</strong>
                    {{-- <p class="text-xs">
                        (value:
                        Rp{{ number_format($transaction->total_discount, 0, ',', '.') }})
                    </p> --}}
                </span>
            @else
                <span class="text-end">
                    <strong>-</strong>
                </span>
            @endif
        </div>
        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Voucher Type</strong>
            </span>
            @if (isset($transaction->voucher))
                <span class="text-end">
                    <strong>{{ str_replace('_', ' ', ucfirst($transaction->voucher->type)) }}</strong>
                </span>
            @else
                <span class="text-end">
                    <strong>-</strong>
                </span>
            @endif
        </div>

        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Total Discount</strong>
            </span>
            <span class="text-end">
                <strong>Rp{{ number_format($transaction->total_discount, 0, ',', '.') }}</strong>
            </span>
        </div>

        {{-- <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Admin Fee</strong>
            </span>
            <span class="text-end">
                <strong>Rp0</strong>
            </span>
        </div> --}}

    </section>

    <section class="flex flex-col gap-3 py-3 border-b border-gray-300">
        <div class="flex flex-row gap-5 w-full justify-between">
            <span>
                <strong>Total</strong>
            </span>
            <span class="text-end">
                <strong>Rp{{ number_format($transaction->total, 0, ',', '.') }}</strong>
            </span>
        </div>
    </section>
</div>

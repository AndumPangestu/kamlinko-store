<x-app-layout>
    <x-slot name="header">
        <!-- Breadcrumbs -->
        <nav class="text-gray-500 text-sm" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center">
                <li class="">
                    <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h3.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                </li> <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.transaction-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg"> Transaction Management
                    </a>
                </li>
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.transaction-management.view-transaction-details', $transaction->id) }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg"> Transaction Details
                    </a>
                </li>
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.transaction-management.view-invoice', $transaction->id) }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg"> Transaction Invoice
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Transaction Invoice') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('View transaction invoice for #') . $transaction->invoice_number . __(' here') }}
    </x-slot>

    <div class="h-[5rem] flex gap-10 items-center">
        <button onclick="printInvoice()"
            class="flex flex-row gap-3 items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4">
            <span class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="2.354a" fill="currentColor"
                    class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                    <path
                        d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                </svg>
            </span>
            <p>Print Invoice</p>
        </button>

    </div>

    <div class="flex items-center justify-center bg-gray-100 flex-col gap-5 py-6">
        <section class="w-[50vw] p-5 h-full bg-white flex flex-col gap-5 pb-[5rem]" id="invoice">
            <div class="grid grid-cols-2 justify-between items-center">
                <span class="flex ps-[3.75rem]">
                    <svg width="50" height="50" viewBox="0 0 100 100" class="scale-200 scale-[4]"
                        xmlns="http://www.w3.org/2000/svg">
                        {!! file_get_contents(public_path('assets/logo-re-colored-text.png')) !!}
                    </svg>
                </span>
                <h1 class="text-2xl font-bold text-end">Faktur #{{ $transaction->invoice_number }}</h1>
            </div>

            <span class="flex flex-col">
                <h3 class="font-semibold">ID Transaksi:</h3>
                <p class="font-light">{{ $transaction->id }}</p>
            </span>
            <div class="grid grid-cols-3">
                <span class="flex flex-col">
                    <h3 class="font-semibold">Metode Pembayaran:</h3>
                    <p class="font-light">{{ $transaction->payment_method }}</p>
                </span>
                <span class="flex flex-col">
                    <h3 class="font-semibold">Status:</h3>
                    <p class="font-light">{{ $transaction->transaction_status }}</p>
                </span>
                <span class="flex flex-col">
                    <h3 class="font-semibold">Tanggal:</h3>
                    <p class="font-light">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</p>
                </span>
            </div>
            <div class="flex flex-col">
                <h2 class="font-semibold text-xl">Item</h2>
                <table>
                    <thead>
                        <tr class="border border-gray-200 bg-gray-200">
                            <th class="py-2">Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->transactionItems as $item)
                            <tr>
                                <td class="border border-gray-200 py-3 px-2">
                                    <p> {{ $item->productType->product->name }}</p>
                                    <p> {{ $item->productType->name }}</p>
                                </td>
                                <td class="border border-gray-200 py-3 px-2">{{ $item->quantity }}</td>
                                <td class="border border-gray-200 py-3 px-2">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid grid-cols-3">
                <span class="flex flex-col">
                    <h3 class="font-semibold">Subtotal:</h3>
                    <p class="font-light">Rp{{ number_format($transaction->subtotal, 0, ',', '.') }} <span
                            class="text-gray-500">*)</span>
                    </p>
                </span>
                <span class="flex flex-col">
                    <h3 class="font-semibold">Biaya Pengiriman:</h3>
                    <p class="font-light">Rp{{ number_format($transaction->delivery_fee, 0, ',', '.') }}</p>
                </span>
                <span class="flex flex-col">
                    <h3 class="font-semibold">Total:</h3>
                    <p class="font-light"> Rp{{ number_format($transaction->total, 0, ',', '.') }}</p>
                </span>
            </div>
            <span>
                <i class="italic text-gray-500">* Harga sudah termasuk PPN 11% </i>
            </span>
            <span>
                <h3 class="font-semibold">Pajak:</h3>
                <p class="font-light">Rp{{ number_format(($transaction->subtotal * 11) / 100, 0, ',', '.') }}</p>
            </span>

            <span>
                <h2 class="font-semibold text-xl">Pengiriman & Alamat</h2>
            </span>
            <span>
                <h3 class="font-semibold">Metode Pengiriman:</h3>
                <p class="font-light">{{ $transaction->delivery_method }}</p>
            </span>
            <span>
                <h3 class="font-semibold">Layanan Pengiriman:</h3>
                <p class="font-light">{{ $transaction->delivery_service }}</p>
            </span>
            <span>
                <h3 class="font-semibold">Alamat Pengiriman:</h3>
                <p class="font-light">{{ $transaction->userAddress->receiver_name }}</p>
                <p class="font-light">{{ $transaction->userAddress->address }}</p>
                <span class="flex flex-row gap-1">
                    <p class="font-light">{{ $transaction->userAddress->subdistrict }},</p>
                    <p class="font-light">{{ $transaction->userAddress->city }},</p>
                    <p class="font-light">{{ $transaction->userAddress->province }},</p>
                    <p class="font-light">{{ $transaction->userAddress->postal_code }}</p>
                </span>
            </span>
        </section>
    </div>

</x-app-layout>

<script>
    function printInvoice() {
        // Disable all hyperlinks within the #invoice section
        const links = document.querySelectorAll('#invoice a');
        links.forEach(link => {
            link.setAttribute('data-href', link.getAttribute('href'));
            link.removeAttribute('href');
        });

        // Add a brief delay before triggering print to ensure changes take effect
        setTimeout(() => {
            // Trigger print
            window.print();

            // Restore all hyperlinks after printing
            links.forEach(link => {
                link.setAttribute('href', link.getAttribute('data-href'));
                link.removeAttribute('data-href');
            });
        }, 100); // Adjust the timeout value if necessary
    }
</script>

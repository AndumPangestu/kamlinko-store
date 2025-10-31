<div class="flex flex-col gap-4">
    @if (count($data) == 0)
        <div class="text-gray-600 text-center">
            <p>No Transaction found.</p>
        </div>
    @else
        <div class="overflow-x-auto border rounded-xl ">
            <table class="min-w-full table-auto">
                <!-- Table Header -->
                <thead class="divide-y divide-gray-200">
                    <tr>
                        <th id="header-id" class="px-4 py-3 text-left text-lg font-semibold text-gray-800 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=id&sort_order={{ $sortBy === 'id' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Invoice Number</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'id' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'id' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th id="header-customer" class="px-4 py-3 text-left text-lg font-semibold text-gray-800 w-1/5">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=user.name&sort_order={{ $sortBy === 'user.name' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Customer Name</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'user.name' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'user.name' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th id="header-status" class="px-4 py-3 text-left text-lg font-semibold text-gray-800 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=transaction_status&sort_order={{ $sortBy === 'transaction_status' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'transaction_status' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'transaction_status' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th id="header-total" class="px-4 py-3 text-left text-lg font-semibold text-gray-800 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=total&sort_order={{ $sortBy === 'total' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Total</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'total' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'total' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th id="header-created" class="px-4 py-3 text-left text-lg font-semibold text-gray-800 w-1/4">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=created_at&sort_order={{ $sortBy === 'created_at' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Created at</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'created_at' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'created_at' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-right text-lg font-semibold text-gray-800 w-1/4">
                            <div class="flex flex-row justify-end items-center gap-5"><span>Action</span></div>
                        </th>
                    </tr>
                </thead>

                <!-- Table Footer - Pagination -->
                <tfoot class="border-t">
                    <tr>
                        <td colspan="7">
                            {{ $data->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder, 'search' => request('search')])->links() }}
                        </td>
                    </tr>
                </tfoot>

                <!-- Table Body -->
                <tbody>
                    @foreach ($data as $transaction)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-400 truncate">
                                {{ $transaction->invoice_number }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $transaction->user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ $transaction->transaction_status }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                Rp{{ number_format($transaction->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                {{ $transaction->created_at->format('d M, Y h:m:s') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3 text-gray-500 items-center h-full w-full">
                                    <a href="{{ route('admin.transaction-management.view-transaction-details', $transaction->id) }}"
                                        class="mx-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        // Do the work...
        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table');
            Array.from(table.querySelectorAll('tbody > tr'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this
                    .asc))
                .forEach(tr => table.querySelector('tbody').appendChild(tr));
        })));
    });
</script>

<div class="flex flex-col gap-4">
    @if (count($data) == 0)
        <div class="text-gray-600 text-center">
            <p>No Voucher found.</p>
            <a href="{{ route('admin.voucher-management.create') }}" class="text-blue-600 underline">
                Add new Voucher
            </a>
        </div>
    @else
        <div class="overflow-x-auto border rounded-xl ">
            <table class="min-w-full table-auto">
                <!-- Table Header -->
                <thead class="divide-y divide-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=name&sort_order={{ $sortBy === 'name' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Name</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'name' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'name' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=code&sort_order={{ $sortBy === 'code' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Code</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'code' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'code' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center  font-semibold text-gray-700 w-1/8">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=quantity&sort_order={{ $sortBy === 'quantity' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Quantity</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'quantity' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'quantity' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center  font-semibold text-gray-700 w-1/8">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=used&sort_order={{ $sortBy === 'used' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Used</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'used' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'used' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=value_percentage&sort_order={{ $sortBy === 'value_percentage' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Value (%)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    class="{{ $sortBy === 'value_percentage' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'value_percentage' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=value_fixed&sort_order={{ $sortBy === 'value_fixed' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Value (Rp)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    class="{{ $sortBy === 'value_fixed' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'value_fixed' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=minimum_transaction_value&sort_order={{ $sortBy === 'minimum_transaction_value' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Minimum Transaction</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    class="{{ $sortBy === 'minimum_transaction_value' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'minimum_transaction_value' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=is_one_time_use&sort_order={{ $sortBy === 'is_one_time_use' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span class="text-sm">One time use?</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    class="{{ $sortBy === 'is_one_time_use' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'is_one_time_use' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=start_date&sort_order={{ $sortBy === 'start_date' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>Start Date</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'start_date' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'start_date' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left  font-semibold text-gray-700 w-1/6">
                            <a class="flex flex-row gap-2 items-center"
                                href="?sort_by=end_date&sort_order={{ $sortBy === 'end_date' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                                <span>End Date</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    class="{{ $sortBy === 'end_date' ? 'text-blue-500' : 'text-gray-400' }} {{ $sortBy === 'end_date' && $sortOrder == 'desc' ? 'scale-x-[-1]' : '' }} bi bi-arrow-down-up"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-right  font-semibold text-gray-700 w-1/12">
                            <div class="flex flex-row justify-end items-center gap-5">
                                <span>
                                    Action
                                </span>
                                <a href="{{ route('admin.voucher-management.create') }}"
                                    class="border border-gray-400 rounded-md p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                </a>
                            </div>
                        </th>
                    </tr>
                </thead>
                <!-- Table Footer - Pagination -->
                <tfoot class="border-t">
                    <tr>
                        <td colspan="12">
                            {{ $data->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder])->links() }} </td>
                    </tr>
                </tfoot>
                <!-- Table Body -->
                <tbody>
                    @foreach ($data as $voucher)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ $voucher->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ $voucher->code }}
                            </td>

                            <td class="px-4 py-3 text-center text-sm text-gray-400">
                                {{ $voucher->quantity }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-400">
                                {{ $voucher->used }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-400">
                                {{ $voucher->value_percentage }}%
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                Rp{{ number_format($voucher->value_fixed, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                Rp{{ number_format($voucher->minimum_transaction_value, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $voucher->is_one_time_use == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $voucher->is_one_time_use == 1 ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                {{ $voucher->start_date }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                {{ $voucher->end_date }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3 text-gray-700 items-center h-full w-full">
                                    <a href="{{ route('admin.voucher-management.view-voucher-details', $voucher->id) }}"
                                        class="mx-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                    <span class="mx-2 cursor-pointer">
                                        <form id="delete-voucher-form" class="flex items-center" method="POST"
                                            action="{{ route('admin.voucher-management.destroy', $voucher->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="29" height="20"
                                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                    <path
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </span>
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
    document.addEventListener('DOMContentLoaded', (event) => {
        const deleteVoucherForm = document.getElementById('delete-voucher-form');
        deleteVoucherForm.addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to delete this voucher?')) {
                event.preventDefault();
            }
        });
    });
</script>

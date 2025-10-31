<x-app-layout>
    <x-slot name="searchBar">
        <x-search-input placeholder="Search by Invoice Number" value="{{ isset($search) ? $search : '' }}" />
    </x-slot>
    <x-slot name="header">
        <!-- Breadcrumbs -->
        <nav class="text-gray-500 text-sm mb-2" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center">
                <li class="">
                    <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                </li>
                <span class="mx-2">></span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.transaction-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Transaction Management
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Transaction Management') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('Welcome to your transaction management!') }}
    </x-slot>

    <div class="py-3">
        <div class="">
            <div class="flex justify-end pb-2">
                <a href="{{ route('admin.transaction-management.export-excel') }}"
                    class="flex flex-row gap-3 items-center justify-center w-fit text-white bg-blue-500 hover:bg-blue-700 rounded-xl p-3">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">
                            <path
                                d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5zM3 12v-2h2v2zm0 1h2v2H4a1 1 0 0 1-1-1zm3 2v-2h3v2zm4 0v-2h3v1a1 1 0 0 1-1 1zm3-3h-3v-2h3zm-7 0v-2h3v2z" />
                        </svg>
                    </span>
                    <p>Export to Excel</p>
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('message'))
                    <div class="text-green-500 text-center">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="text-red-500 text-center">
                        {{ session('error') }}
                    </div>
                @endif
                <div id="transaction-list">
                    @include('components.transaction.transaction-list', ['data' => $data])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value;
            fetch(`{{ route('admin.transaction-management') }}?search=${query}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('transaction-list').innerHTML = data;
                });
        });
    });
</script>

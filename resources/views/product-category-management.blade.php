<x-app-layout>
    <x-slot name="searchBar">
        <x-search-input placeholder="Search by Name" />
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
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.product-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Product Management
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
                    <a href="{{ route('admin.product-category-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Category
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Category Management') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('Manage your categories here') }}
    </x-slot>

    <div class="py-12">
        <div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">
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

                    <div id="category-list">
                        @include('components.product.category.category-list', ['categories' => $data])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Dynamic Search -->
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value;
            fetch(`{{ route('admin.product-category-management') }}?search=${query}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('category-list').innerHTML = data;
                });
        });
    });
</script>

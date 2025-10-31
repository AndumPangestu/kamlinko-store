<x-app-layout>
    <x-slot name="header">
        <!-- Breadcrumbs -->
        <nav class="text-gray-500 text-sm mb-2" aria-label="Breadcrumb">
            <ol class=  "list-reset flex items-center">
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
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Dashboard
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Dashboard') }}
    </x-slot>

    <x-slot name="headerDesc">
        {{ __('Welcome to your dashboard!') }}
    </x-slot>

    <div class="py-6">
        <div class="">
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

            <!-- Filter for all data below -->
            <section class="pb-5 mx-10 flex flex-row gap-2 justify-end items-center">
                <form action="{{ route('admin.dashboard') }}" method="GET"
                    class="flex items-center justify-end space-x-2 text-sm">
                    <div class="flex flex-row gap-3 items-center">
                        <div class="flex flex-row items-center gap-1">
                            <label for="selected_category" class="text-gray-700">Category:</label>
                            <div class="relative w-[20rem]">
                                <div id="selected-category"
                                    class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center">
                                    <span id="selected-category-text">
                                        {{ old('selected_category')
                                            ? str_repeat('--', $categories->firstWhere('id', old('selected_category'))->level ?? 0) .
                                                ' ' .
                                                $categories->firstWhere('id', old('selected_category'))->name
                                            : (isset($selected_category)
                                                ? str_repeat('--', $categories->firstWhere('id', $selected_category)->level ?? 0) .
                                                    ' ' .
                                                    $categories->firstWhere('id', $selected_category)->name
                                                : 'No category selected') }}
                                    </span>

                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div id="category-list"
                                    class="border border-gray-300 rounded p-1 w-full absolute bg-white z-10 max-h-56 overflow-auto"
                                    style="display: none;">
                                    <input type="text" id="category-search"
                                        class="border border-gray-300 rounded p-1 w-full mb-1"
                                        placeholder="Search Categories">
                                    <div class="option" data-value="">No Category</div>
                                    @foreach ($categories as $parentCategory)
                                        @if ($parentCategory->parent_id === null)
                                            @include('components.categories-dropdown', [
                                                'categories' => collect([$parentCategory]),
                                                'level' => 0,
                                                'selectedCategoryId' => old(
                                                    'selected_category',
                                                    $brand->category_id ?? ''),
                                            ])
                                        @endif
                                    @endforeach
                                </div>
                                <input type="hidden" name="selected_category" id="selected_category"
                                    value="{{ old('selected_category', isset($brand) ? $brand->category_id : '') }}">
                            </div>

                        </div>
                        <div>
                            <label for="start_date" class="text-gray-700">from:</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ isset($start_date) ? \Carbon\Carbon::parse($start_date)->format('Y-m-d') : '' }}"
                                class="border border-gray-400 rounded-lg p-1 text-sm">
                        </div>
                        <div>

                            <label for="end_date" class="text-gray-700">to:</label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ isset($end_date) ? \Carbon\Carbon::parse($end_date)->format('Y-m-d') : '' }}"
                                class="border border-gray-400 rounded-lg p-1 text-sm">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-400 px-3 py-1 rounded-xl text-white">Filter</button>
                    </div>
                </form>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-end space-x-2 text-sm">
                    <button class="bg-red-400 px-3 py-1 rounded-xl text-white">Reset</button>
                </a>
            </section>
            <!-- Metric Boxes -->
            <section class="bg-white px-10 overflow-hidden">
                <div class="grid grid-cols-4 gap-5">
                    <x-metric-box title="Daily Revenue"
                        border-color="border-gray-400">Rp{{ number_format($totalDailyRevenue, 0, ',', '.') }}</x-metric-box>
                    <x-metric-box title="Monthly Revenue"
                        border-color="border-gray-400">Rp{{ number_format($totalMonthlyRevenue, 0, ',', '.') }}</x-metric-box>
                    <x-metric-box title="Yearly Revenue"
                        border-color="border-gray-400">Rp{{ number_format($totalYearlyRevenue, 0, ',', '.') }}</x-metric-box>
                    <x-metric-box title="Payment received awaiting for review"
                        border-color="border-gray-400">{{ $awaitingPaymentApproval }}</x-metric-box>
                </div>
            </section>

            <!-- Latest Transactions -->
            <section class="flex justify-center py-5">
                <div x-data="latestTransactions" class="w-[95%] border border-gray-400 rounded-xl">
                    <button x-on:click="toggle"
                        class="w-full inline-flex items-center justify-between px-5 py-5 rounded-lg text-normal font-medium leading-5 text-gray-700 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                        <div class="flex flex-row items-center">
                            <span class="">Latest Transactions</span>
                        </div>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                            class="w-4 h-4 transition-transform duration-200 transform">
                            <path fill-rule="evenodd"
                                d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2">
                        <table class="w-full table-fixed">
                            <thead>
                                <tr>
                                    <th class="px-4">
                                        <input type="text" class="w-full rounded-lg border-gray-400"
                                            x-on:input="updateSearchId" placeholder="Search ID">
                                    </th>
                                    <th class="px-4">
                                        <input type="text" class="w-full rounded-lg border-gray-400"
                                            x-on:input="updateSearchUser" placeholder="Search User">
                                    </th>
                                    <th class="px-4">&nbsp;</th>
                                    <th class="px-4">&nbsp;</th>
                                    <th class="px-4">
                                        <select class="w-full text-gray-800 font-poppins rounded-lg border-gray-400"
                                            x-on:change="updateSearchStatus">
                                            <option value="">All Statuses</option>
                                            <template x-for="status in statusOptions" :key="status">
                                                <option :value="status" x-text="status"></option>
                                            </template>
                                        </select>
                                    </th>
                                    <th class="px-4">
                                        <input type="text" class="w-full rounded-lg border-gray-400"
                                            x-on:input="updateSearchPayment" placeholder="Search Payment Method">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-x">
                                <template x-for="tx in formatted" :key="tx.id">
                                    <tr>
                                        <td class="px-4 py-2" x-text="tx.id"></td>
                                        <td class="px-4 py-2" x-text="tx.name"></td>
                                        <td class="px-4 py-2 text-center" x-text="tx.total"></td>
                                        <td class="px-4 py-2 text-end" x-text="tx.date"></td>
                                        <td class="px-4 py-2 text-end" x-text="tx.transaction_status"></td>
                                        <td class="px-4 py-2 text-end" x-text="tx.payment_method"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Most and least viewed products -->
            <section class="flex justify-end w-full px-10 pt-5">
                <div x-data="mostAndLeastViewedProducts"
                    class="p-5 w-full border border-gray-400 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-100">
                    <button x-on:click="toggle"
                        class=" w-full inline-flex items-center justify-between rounded-lg text-normal font-medium text-gray-700 transition duration-150 ease-in-out">
                        <div class="flex flex-row items-center">
                            <span>Most and Least Viewed Product</span>
                        </div>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                            class="w-4 h-4 transition-transform duration-200 transform">
                            <path fill-rule="evenodd"
                                d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="items-center gap-10 border-gray-200">
                        <!-- Products -->
                        <div class="flex flex-row justify-center items-center mt-5">
                            <!-- Most Viewed Product -->
                            <section class="flex flex-col justify-end w-full pr-10">
                                <div class="p-5 border rounded-xl border-gray-300">
                                    <h2 class="text-center text-lg pb-3">Most Viewed Product</h2>
                                    <!-- If there is a product -->
                                    @if (!is_null($mostViewedProduct))
                                        <section>
                                            <div class="grid grid-cols-2 gap-5 items-center pb-5">
                                                <div class="flex items-center justify-start">
                                                    @if ($mostViewedProduct->productType->count() > 0)
                                                        <img src="{{ $mostViewedProduct->productType->first()->getMedia('*')->first()->preview_url }}"
                                                            alt="Product Image"
                                                            class="w-56 h-56 object-cover rounded-md">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="80"
                                                            height="80" fill="currentColor" class="bi bi-box"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <ul class="text-gray-700 space-y-2">
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Name</span>
                                                            <span
                                                                class="text-end col-span-2">{{ $mostViewedProduct->name }}</span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Category</span>
                                                            <span
                                                                class="text-end col-span-2">{{ $mostViewedProduct->category->name }}</span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Price</span>
                                                            <span class="text-end col-span-2">
                                                                @if ($mostViewedProduct->productType->count() == 1)
                                                                    Rp{{ number_format($mostViewedProduct->productType->first()->price, 0, ',', '.') }}
                                                                @elseif ($mostViewedProduct->productType->count() > 1)
                                                                    Rp{{ number_format($mostViewedProduct->productType->min('price'), 0, ',', '.') }}
                                                                    -
                                                                    Rp{{ number_format($mostViewedProduct->productType->max('price'), 0, ',', '.') }}
                                                                @else
                                                                    Not Set
                                                                @endif
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Stock</span>
                                                            <span class="text-end col-span-2">
                                                                @if ($mostViewedProduct->productType->count() == 1)
                                                                    {{ $mostViewedProduct->productType->first()->stock }}
                                                                @elseif ($mostViewedProduct->productType->count() > 1)
                                                                    {{ $mostViewedProduct->productType->sum('stock') }}
                                                                @else
                                                                    Not Set
                                                                @endif
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Brand</span>
                                                            <span class="gap-2 items-center text-end col-span-2">
                                                                {{ $mostViewedProduct->brand->name }}
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Product added at</span>
                                                            <span class="gap-2 items-center text-end col-span-2">
                                                                {{ $leastViewedProduct->created_at }}
                                                            </span>
                                                        </li>

                                                        <li class="flex justify-between">
                                                            &nbsp;
                                                        </li>
                                                        <li class="grid grid-cols-3">
                                                            <span class="font-semibold text-xl">Views</span>
                                                            <span
                                                                class="flex flex-row col-span-2 gap-2 items-center justify-end text-xl">
                                                                {{ $mostViewedProduct->views }}
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="currentColor"
                                                                    class="bi bi-eye text-gray-500"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                                    <path
                                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                                </svg>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </section>
                                    @else
                                        <!-- If there is no product -->
                                        <section class="py-5 flex items-center justify-center">
                                            No products found with this filter!
                                        </section>
                                    @endif
                                </div>
                            </section>
                            <!-- Least Viewed Product -->
                            <section class="flex flex-col justify-end w-full pr-10">
                                <div class="p-5 border rounded-xl border-gray-300">
                                    <h2 class="text-center text-lg pb-3">Least Viewed Product</h2>
                                    <!-- If there is a product -->
                                    @if (!is_null($leastViewedProduct))
                                        <section>
                                            <div class="grid grid-cols-2 gap-5 items-center pb-5">
                                                <div class="flex items-center justify-start">
                                                    @if ($leastViewedProduct->productType->count() > 0)
                                                        <img src="{{ $leastViewedProduct->productType->first()->getMedia('*')->first()->preview_url }}"
                                                            alt="Product Image"
                                                            class="w-56 h-56 object-cover rounded-md">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="80"
                                                            height="80" fill="currentColor" class="bi bi-box"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <ul class="text-gray-700 space-y-2">
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Name</span>
                                                            <span
                                                                class="text-end col-span-2">{{ $leastViewedProduct->name }}</span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Category</span>
                                                            <span
                                                                class="text-end col-span-2">{{ $leastViewedProduct->category->name }}</span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Price</span>
                                                            <span class="text-end col-span-2">
                                                                @if ($leastViewedProduct->productType->count() == 1)
                                                                    Rp{{ number_format($leastViewedProduct->productType->first()->price, 0, ',', '.') }}
                                                                @elseif ($leastViewedProduct->productType->count() > 1)
                                                                    Rp{{ number_format($leastViewedProduct->productType->min('price'), 0, ',', '.') }}
                                                                    -
                                                                    Rp{{ number_format($leastViewedProduct->productType->max('price'), 0, ',', '.') }}
                                                                @else
                                                                    Not Set
                                                                @endif
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Stock</span>
                                                            <span class="text-end col-span-2">
                                                                @if ($leastViewedProduct->productType->count() == 1)
                                                                    {{ $leastViewedProduct->productType->first()->stock }}
                                                                @elseif ($leastViewedProduct->productType->count() > 1)
                                                                    {{ $leastViewedProduct->productType->sum('stock') }}
                                                                @else
                                                                    Not Set
                                                                @endif
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Brand</span>
                                                            <span class="gap-2 items-center text-end col-span-2">
                                                                {{ $leastViewedProduct->brand->name }}
                                                            </span>
                                                        </li>
                                                        <li class="grid grid-cols-3 justify-between">
                                                            <span class="font-semibold">Product added at</span>
                                                            <span class="gap-2 items-center text-end col-span-2">
                                                                {{ $leastViewedProduct->created_at }}
                                                            </span>
                                                        </li>
                                                        <li class="flex justify-between">
                                                            &nbsp;
                                                        </li>
                                                        <li class="grid grid-cols-3">
                                                            <span class="font-semibold text-xl">Views</span>
                                                            <span
                                                                class="flex flex-row col-span-2 gap-2 items-center justify-end text-xl">
                                                                {{ $leastViewedProduct->views }}
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="currentColor"
                                                                    class="bi bi-eye text-gray-500"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                                    <path
                                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                                </svg>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </section>
                                    @else
                                        <!-- If there is no product -->
                                        <section class="py-5 flex items-center justify-center">
                                            No products found with this filter!
                                        </section>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Last 30 Days Transactions -->
            <section class="flex justify-center pt-5">
                <div x-data="last30DaysTransactions"
                    class="px-5 bg-white w-full lg:w-[95%] border border-gray-400 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-100">
                    <button x-on:click="toggle"
                        class="py-5 w-full inline-flex items-center justify-between rounded-lg text-normal font-medium text-gray-700 transition duration-150 ease-in-out">
                        <div class="flex flex-row items-center">
                            <span>Completed Transactions of the Last 30 Days</span>
                        </div>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                            class="w-4 h-4 transition-transform duration-200 transform">
                            <path fill-rule="evenodd"
                                d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open"
                        class="py-5 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <x-line-chart canvasId="transactionsWithinPeriod" :chartData="$transactionsWithinPeriod" label="Transactions"
                            xTitle="Date" yTitle="Total"></x-line-chart>
                    </div>
                </div>
            </section>

            <div class="flex flex-row justify-center mt-5">
                <!-- Transactions Per Month -->
                <section class="flex justify-end w-full pr-5">
                    <div x-data="transactionsPerMonth"
                        class="px-5 bg-white lg:w-[95%] border border-gray-400 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-100">
                        <button x-on:click="toggle"
                            class="py-5 w-full inline-flex items-center justify-between rounded-lg text-normal font-medium text-gray-700 transition duration-150 ease-in-out">
                            <div class="flex flex-row items-center">
                                <span>Completed Transactions Per Month</span>
                            </div>
                            <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                                class="w-4 h-4 transition-transform duration-200 transform">
                                <path fill-rule="evenodd"
                                    d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd">
                                </path>
                            </svg>
                        </button>
                        <div x-show="open"
                            class="py-5 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <x-line-chart canvasId="transactionPerMonth" :chartData="$transactionPerMonth"
                                label="Transactions Per Month" xTitle="Month" yTitle="Total">
                            </x-line-chart>
                        </div>
                    </div>
                </section>
                <!-- Transactions Per Weekday -->
                <section class="flex justify-start w-full ps-5">
                    <div x-data="transactionsPerWeekday"
                        class="px-5 bg-white lg:w-[95%] border border-gray-400 overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-100">
                        <button x-on:click="toggle"
                            class="py-5 w-full inline-flex items-center justify-between rounded-lg text-normal font-medium text-gray-700 transition duration-150 ease-in-out">
                            <div class="flex flex-row items-center"> <span>Completed Transactions Per Weekday in the
                                    Last 30 Days</span> </div> <svg fill="currentColor" viewBox="0 0 20 20"
                                :class="rotateClasses" class="w-4 h-4 transition-transform duration-200 transform">
                                <path fill-rule="evenodd"
                                    d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open"
                            class="py-5 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <x-line-chart canvasId="transactionPerWeekday" :chartData="$transactionPerWeekday"
                                label="Transactions Per Weekday" xTitle="Weekday" yTitle="Total"></x-line-chart>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Category Dropdown
        const searchInput = document.getElementById('category-search');
        const categoryList = document.getElementById('category-list');
        const selectedCategory = document.getElementById('selected-category');
        const selectedCategoryText = document.getElementById('selected-category-text');
        const options = categoryList.querySelectorAll('.option');
        const hiddenInput = document.getElementById('selected_category');

        selectedCategory.addEventListener('click', function() {
            categoryList.style.display = categoryList.style.display === 'none' ? 'block' : 'none';
        });

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
            options.forEach(function(option) {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(query) ? '' : 'none';
            });
        });

        document.addEventListener('click', function(event) {
            if (!selectedCategory.contains(event.target) && !categoryList.contains(event.target)) {
                categoryList.style.display = 'none';
            }
        });

        options.forEach(function(option) {
            option.addEventListener('click', function() {
                selectedCategoryText.textContent = option.textContent.trim();
                hiddenInput.value = option.dataset.value;
                options.forEach(function(opt) {
                    opt.classList.remove('selected');
                });
                option.classList.add('selected');
                categoryList.style.display = 'none';
            });

            option.addEventListener('mouseover', function() {
                option.style.backgroundColor = '#f0f0f0';
            });

            option.addEventListener('mouseout', function() {
                option.style.backgroundColor = '';
            });
        });
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('last30DaysTransactions', () => ({
            open: true,
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('transactionsPerMonth', () => ({
            open: true,
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('transactionsPerWeekday', () => ({
            open: true,
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('mostAndLeastViewedProducts', () => ({
            open: true,
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('latestTransactions', () => ({
            open: true,
            searchId: '',
            searchUser: '',
            searchTotal: '',
            searchStatus: '',
            searchPayment: '',
            statusOptions: ['Waiting Payment', 'Payment Received', 'Payment Rejected',
                'On Process',
                'On Delivery', 'Delivered', 'Completed', 'Cancelled'
            ],
            displayedTransactions: 5,
            transactions: @json($latestTransactions),
            filtered: [],
            formatted: [],

            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },

            init() {
                this.filterTransactions();
            },

            filterTransactions() {
                this.filtered = this.transactions.filter(tx => {
                    return (!this.searchId || tx.id.toLowerCase().includes(this.searchId
                            .toLowerCase())) &&
                        (!this.searchUser || tx.user.name.toLowerCase().includes(this
                            .searchUser.toLowerCase())) &&
                        (!this.searchTotal || tx.total.toString().toLowerCase()
                            .includes(
                                this.searchTotal.toLowerCase())) &&
                        (!this.searchStatus || tx.transaction_status.toLowerCase()
                            .includes(
                                this.searchStatus.toLowerCase())) &&
                        (!this.searchPayment || tx.payment_method.toLowerCase()
                            .includes(
                                this.searchPayment.toLowerCase()));
                }).slice(0, this.displayedTransactions);

                this.formatData();
            },

            formatData() {
                this.formatted = this.filtered.map(tx => ({
                    id: tx.id.length > 8 ? tx.id.substring(0, 8) + '...' : tx.id,
                    name: tx.user.name.length > 15 ? tx.user.name.substring(0, 15) +
                        '...' : tx.user.name,
                    total: `Rp${new Intl.NumberFormat('id-ID').format(tx.total)}`,
                    date: new Date(tx.created_at).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }),
                    transaction_status: tx.transaction_status,
                    payment_method: tx.payment_method
                }));
            },

            updateSearchId(event) {
                this.searchId = event.target.value;
                this.filterTransactions();
            },

            updateSearchUser(event) {
                this.searchUser = event.target.value;
                this.filterTransactions();
            },

            updateSearchTotal(event) {
                this.searchTotal = event.target.value;
                this.filterTransactions();
            },

            updateSearchStatus(event) {
                this.searchStatus = event.target.value;
                this.filterTransactions();
            },

            updateSearchPayment(event) {
                this.searchPayment = event.target.value;
                this.filterTransactions();
            },

            toggle() {
                this.open = !this.open;
            }
        }));
    });
</script>

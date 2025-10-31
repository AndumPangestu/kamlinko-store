<!-- filepath: /d:/Project/backend-ecommerce/resources/views/product-details.blade.php -->
<x-app-layout>
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
                    <a href="{{ route('admin.product-management.view-product-details', $product->id) }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Product Details
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Product Details') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('View and edit product for ') . $product->name . __(' here') }}
    </x-slot>
    <div class="grid grid-cols-4 gap-5 py-12">
        <nav class="border rounded-lg p-5 h-fit grid grid-flow-row gap-5">
            <a href="{{ route('admin.product-management.view-product-details', [$product->id, 'mode' => 'basic-details']) }}"
                @class([
                    'p-2 rounded',
                    'bg-blue-100' => $mode === 'basic-details',
                    'bg-white' => $mode !== 'basic-details',
                ])>
                Basic Details
            </a>
            <a href="{{ route('admin.product-management.list-product-types', [$product->id, 'mode' => 'product-type']) }}"
                @class([
                    'p-2 rounded',
                    'bg-blue-100' => $mode === 'product-type',
                    'bg-white' => $mode !== 'product-type',
                ])>
                Product Types/Variants
            </a>
            <a href="{{ route('admin.product-management.view-product-statistics', [$product->id, 'mode' => 'product-statistics']) }}"
                @class([
                    'p-2 rounded',
                    'bg-blue-100' => $mode === 'product-statistics',
                    'bg-white' => $mode !== 'product-statistics',
                ])>
                Product Statistics
            </a>
        </nav>
        <div class="col-span-3">
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

            @if ($mode === 'basic-details')
                <x-product.product-basic-details :product="$product" :categories="$categories" :tags="$tags"
                    :brands="$brands" />
            @elseif($mode === 'product-type')
                <div class="pb-3">
                    <a href="#" id="add-new-product-type" class="text-blue-500 hover:text-blue-700"> + Add New
                        Product Type </a>
                </div>
                <!-- Template for new product type -->
                <template id="new-product-type-template">
                    <div class="new-product-type-form pb-3">
                        <x-product.new-product-type-details :productid="$product->id" />
                    </div>
                </template>
                <!-- Existing product types -->
                <div id="product-types-list">
                    @foreach ($product->productType->sortByDesc('updated_at') as $type)
                        <div class="pb-3">
                            <x-product.product-type-details :productid="$product->id" :type="$type" />
                        </div>
                    @endforeach
                </div>
            @elseif($mode == 'product-statistics')
                <x-product.product-statistics :product="$product" :transactions="$transactions" />
            @endif

        </div>
    </div>
</x-app-layout>
@if ($mode == 'product-type')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-new-product-type').addEventListener('click', function(e) {
                e.preventDefault();
                if (document.querySelector('.new-product-type-form')) {
                    return;
                }
                const template = document.getElementById('new-product-type-template').content;
                const newProductType = document.importNode(template, true);
                const productTypesList = document.getElementById('product-types-list');
                productTypesList.prepend(newProductType);
            });
        });
    </script>
@endif

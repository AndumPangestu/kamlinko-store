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
                    <a href="{{ route('admin.product-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Add New Product
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Add Product') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('Add a new product here') }}
    </x-slot>

    <div class="py-12" id="product-profile">
        <!-- Message -->
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
        <!-- Product details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">General Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST"
                action="{{ route('admin.product-management.store') }}">
                @csrf
                <span>
                    <label for="name" class="text-lg font-semibold">Product Name</label>
                    <input type="text" name="name" id="product-name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter product name">
                </span>

                <span>
                    <label for="category"><strong>Category</strong>:</label>
                    <div class="relative">
                        <div id="selected-category"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center"
                            onclick="toggleCategoryList()">
                            <span id="selected-category-text">
                                {{ old('category_id')
                                    ? str_repeat('--', $categoryOptions->firstWhere('id', old('category_id'))->level ?? 0) .
                                        $categoryOptions->firstWhere('id', old('category_id'))->name
                                    : (isset($product) && $product->category_id
                                        ? str_repeat('--', $categories->firstWhere('id', $product->category_id)->level ?? 0) .
                                            $categories->firstWhere('id', $product->category_id)->name
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
                                class="border border-gray-300 rounded p-1 w-full mb-1" placeholder="Search Categories">
                            <div class="option" data-value="" onclick="selectCategory(this)">No Category</div>
                            @foreach ($categoryOptions as $parentCategory)
                                @if ($parentCategory->parent_id === null && (!isset($product) || $parentCategory->id != $product->category_id))
                                    @include('components.categories-dropdown', [
                                        'categories' => collect([$parentCategory]),
                                        'level' => 0,
                                        'selectedCategoryId' => old('category_id', $product->category_id ?? ''),
                                    ])
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="category_id" id="category_id"
                            value="{{ old('category_id', isset($product) ? $product->category_id : '') }}">
                    </div>
                </span>

                <span>
                    <label for="tag" class="text-lg font-semibold">Tag</label>
                    <select id="tag" name="tag_id" class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="">Select Tag</option>
                        @foreach ($tagOptions as $tag)
                            <option value="{{ $tag->id }}" {{ old('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}</option>
                        @endforeach
                    </select>
                </span>

                <div class="col-span-2">
                    <label for="brand_id"><strong>Brand:</strong></label>
                    <div class="relative">
                        <div id="selected-brand"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center"
                            onclick="toggleBrandList()">
                            <span
                                id="selected-brand-text">{{ old('brand_id') ? $brandOptions->firstWhere('id', old('brand_id'))->name : (isset($product) && $product->brand_id ? $brandOptions->firstWhere('id', $product->brand_id)->name : 'No brand selected') }}</span>
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div id="brand-list"
                            class="border border-gray-300 rounded p-1 w-full absolute bg-white z-10 max-h-56 overflow-auto"
                            style="display: none;">
                            <input type="text" id="brand-search"
                                class="border border-gray-300 rounded p-1 w-full mb-1" placeholder="Search Brands">
                            <div class="option" data-value="" onclick="selectBrand(this)">No Brand</div>
                            @foreach ($brandOptions as $brand)
                                <div class="option" data-value="{{ $brand->id }}"
                                    {{ old('brand_id', isset($product) && $product->brand_id == $brand->id ? 'data-selected="true"' : '') == $brand->id ? 'data-selected="true"' : '' }}
                                    onclick="selectBrand(this)">
                                    {{ $brand->name }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="brand_id" id="brand_id"
                            value="{{ old('brand_id', isset($product) ? $product->brand_id : '') }}">
                    </div>
                </div>

                <span>
                    <label for="price" class="text-lg font-semibold">Description</label>
                    <input type="text" name="description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter Short Description" value="{{ old('description') }}" />
                </span>

                <span>
                    <label for="long_description" class="text-lg font-semibold">Long Description</label>
                    <x-rich-text-input id="long_description" name="long_description" :initialValue="old('long_description', $product->long_description ?? '')" />
                </span>

                <span>
                    <label for="put_on_highlight" class="text-lg font-semibold">Put on Highlight?</label>
                    <select id="put_on_highlight" name="put_on_highlight"
                        class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="0" {{ old('put_on_highlight') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('put_on_highlight') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </span>
                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">Save
                        Product</button>
                </span>
            </form>
        </section>
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
        const hiddenInput = document.getElementById('category_id');

        function toggleCategoryList() {
            categoryList.style.display = categoryList.style.display === 'none' ? 'block' : 'none';
        }

        function selectCategory(element) {
            selectedCategoryText.textContent = element.textContent.trim();
            hiddenInput.value = element.dataset.value;
            categoryList.style.display = 'none';
        }

        selectedCategory.addEventListener('click', toggleCategoryList);

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
                selectCategory(option);
                options.forEach(function(opt) {
                    opt.classList.remove('selected');
                });
                option.classList.add('selected');
            });

            option.addEventListener('mouseover', function() {
                option.style.backgroundColor = '#f0f0f0';
            });

            option.addEventListener('mouseout', function() {
                option.style.backgroundColor = '';
            });
        });

        // Check for default selected option
        const selectedOption = categoryList.querySelector('.option[data-selected="true"]');
        if (selectedOption) {
            selectCategory(selectedOption);
            selectedOption.classList.add('selected');
        }

        // Brand Dropdown
        const searchBrandInput = document.getElementById('brand-search');
        const brandList = document.getElementById('brand-list');
        const selectedBrand = document.getElementById('selected-brand');
        const selectedBrandText = document.getElementById('selected-brand-text');
        const brandOptions = brandList.querySelectorAll('.option');
        const hiddenBrandInput = document.getElementById('brand_id');

        selectedBrand.addEventListener('click', function() {
            brandList.style.display = brandList.style.display === 'none' ? 'block' : 'none';
        });

        searchBrandInput.addEventListener('input', function() {
            const query = searchBrandInput.value.toLowerCase();
            brandOptions.forEach(function(option) {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(query) ? '' : 'none';
            });
        });

        document.addEventListener('click', function(event) {
            if (!selectedBrand.contains(event.target) && !brandList.contains(event.target)) {
                brandList.style.display = 'none';
            }
        });

        brandOptions.forEach(function(option) {
            option.addEventListener('click', function() {
                selectedBrandText.textContent = option.textContent.trim();
                hiddenBrandInput.value = option.dataset.value;
                brandOptions.forEach(function(opt) {
                    opt.classList.remove('selected');
                });
                option.classList.add('selected');
                brandList.style.display = 'none';
            });

            option.addEventListener('mouseover', function() {
                option.style.backgroundColor = '#f0f0f0';
            });

            option.addEventListener('mouseout', function() {
                option.style.backgroundColor = '';
            });
        });

        // Check for default selected brand option
        const selectedBrandOption = brandList.querySelector('.option[data-selected="true"]');
        if (selectedBrandOption) {
            selectedBrandText.textContent = selectedBrandOption.textContent.trim();
            hiddenBrandInput.value = selectedBrandOption.dataset.value;
            selectedBrandOption.classList.add('selected');
        }
    });
</script>

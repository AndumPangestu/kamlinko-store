<section class="flex flex-row gap-5 md:gap-10 border border-gray-400 rounded-lg p-5">
    <div class="p-5 w-[60vw]">
        <form id="product-details" method="POST" action="{{ route('admin.product-management.update', $product->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="name"><strong>Product Name:</strong></label>
                    <input type="text" id="name" name="name" value="{{ $product->name }}"
                        class="border border-gray-300 rounded p-1 w-full">
                </div>

                <span>
                    <label for="category"><strong>Category</strong>:</label>
                    <div class="relative">
                        <div id="selected-category"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center"
                            >
                            <span id="selected-category-text">
                                {{ old('category_id')
                                    ? str_repeat('--', $categories->firstWhere('id', old('category_id'))->level ?? 0) .
                                        ' ' .
                                        $categories->firstWhere('id', old('category_id'))->name
                                    : (isset($product) && $product->category_id
                                        ? str_repeat('--', $categories->firstWhere('id', $product->category_id)->level ?? 0) .
                                            ' ' .
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
                            <div class="option" data-value="">No Category</div>
                            @foreach ($categories as $parentCategory)
                                @if ($parentCategory->parent_id === null)
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

                <div>
                    <label for="tag_id"><strong>Tag:</strong></label>
                    <select id="tag_id" name="tag_id" class="border border-gray-300 rounded p-1 w-full">
                        @foreach ($tags as $tag)
                            <option value="{{ old('tag_id', $tag->id) }}"
                                {{ old('tag_id', $product->tag_id == $tag->id ? 'selected' : '') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label for="brand_id"><strong>Brand:</strong></label>
                    <div class="relative">
                        <div id="selected-brand"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center"
                            onclick="toggleBrandList()">
                            <span
                                id="selected-brand-text">{{ old('brand_id') ? $brands->firstWhere('id', old('brand_id'))->name : (isset($product) && $product->brand_id ? $brands->firstWhere('id', $product->brand_id)->name : 'No brand selected') }}</span>
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
                            @foreach ($brands as $brand)
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


                <div class="col-span-2">
                    <label for="description"><strong>Description:</strong></label>
                    <textarea id="description" name="description" class="border border-gray-300 rounded p-1 w-full">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-span-2">
                    <label for="long_description"><strong>Long Description:</strong></label>
                    <x-rich-text-input id="long_description" name="long_description" :initialValue="old('long_description', $product->long_description)" />
                </div>

                <div class="col-span-1">
                    <label for="put_on_highlight"><strong>Put on Highlight?:</strong></label>
                    <select id="put_on_highlight" name="put_on_highlight"
                        class="border border-gray-300 rounded p-1 w-full">
                        <option value="1"
                            {{ old('put_on_highlight', $product->put_on_highlight == 1 ? 'selected' : '') == 1 ? 'selected' : '' }}>
                            Yes</option>
                        <option value="0"
                            {{ old('put_on_highlight', $product->put_on_highlight == 0 ? 'selected' : '') == 0 ? 'selected' : '' }}>
                            No</option>
                    </select>
                </div>

                @if (isset($product))
                    <div class="col-span-2">
                        <div class="flex flex-row gap-10">
                            <span class="text-sm text-gray-500">
                                <p>Created at:</p>
                                <p>{{ $product->created_at }}</p>
                                <span>
                                    by
                                    <a class="hover:text-blue-500"
                                        href="{{ isset($product->created_by) ? route('admin.user-management.view-user-details', $product->admin_create->id) : '#' }}">
                                        {{ isset($product->created_by) ? $product->admin_create->name : 'Unknown/Deleted Admin' }}
                                    </a>
                                </span>
                            </span>
                            <span class="text-sm text-gray-500">
                                <p>Updated at:</p>
                                <p>{{ $product->updated_at }}</p>
                                <span>
                                    by
                                    <a class="hover:text-blue-500"
                                        href="{{ isset($product->updated_by) ? route('admin.user-management.view-user-details', $product->admin_update->id) : '#' }}">
                                        {{ isset($product->updated_by) ? $product->admin_update->name : 'Unknown/Deleted Admin' }}
                                    </a>
                                </span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Category Dropdown
        const searchInput = document.getElementById('category-search');
        const categoryList = document.getElementById('category-list');
        const selectedCategory = document.getElementById('selected-category');
        const selectedCategoryText = document.getElementById('selected-category-text');
        const options = categoryList.querySelectorAll('.option');
        const hiddenInput = document.getElementById('category_id');

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

        // Check for default selected option
        const selectedOption = categoryList.querySelector('.option[data-selected="true"]');
        if (selectedOption) {
            selectedCategoryText.textContent = selectedOption.textContent.trim();
            hiddenInput.value = selectedOption.dataset.value;
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

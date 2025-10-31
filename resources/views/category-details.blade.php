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
                    <a href="{{ route('admin.product-category-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Category Management
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
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ isset($category) ? route('admin.product-category-management.view-category-details', $category->id) : route('admin.product-category-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        {{ isset($category) ? 'Update Category' : 'Add Category' }}
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ isset($category) ? __('Update Category') : __('Add Category') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ isset($category) ? __('Update a category here') : __('Add a new category here') }}
    </x-slot>

    <div class="py-12">
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
        <!-- Category details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">Category Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST" enctype="multipart/form-data"
                action="{{ isset($category) ? route('admin.product-category-management.update', $category->id) : route('admin.product-category-management.store') }}">
                @csrf
                @if (isset($category))
                    @method('PUT')
                @endif

                <span>
                    <label for="name" class="text-lg font-semibold">Category Name</label>
                    <input type="text" name="name" id="category-name"
                        value="{{ old('name', $category->name ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter category name">
                </span>

                <span>
                    <label for="description" class="text-lg font-semibold">Description</label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter category description" rows="5">{{ old('description', $category->description ?? '') }}</textarea>
                </span>

                <span>
                    <label for="parent_id"><strong>Parent Category</strong><span
                            class="text-sm text-gray-500">(optional)</span>:</label>
                    <div class="relative">
                        <div id="selected-category"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center">
                            <span id="selected-category-text">
                                {{ old('parent_id')
                                    ? str_repeat('--', $categories->firstWhere('id', old('parent_id'))->level ?? 0) .
                                        ' ' .
                                        $categories->firstWhere('id', old('parent_id'))->name
                                    : (isset($category) && $category->parent_id
                                        ? str_repeat('--', $categories->firstWhere('id', $category->parent_id)->level ?? 0) .
                                            ' ' .
                                            $categories->firstWhere('id', $category->parent_id)->name
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
                                        'selectedCategoryId' => old('parent_id', $category->parent_id ?? ''),
                                    ])
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="parent_id" id="parent_id"
                            value="{{ old('parent_id', isset($category) ? $category->parent_id : '') }}">
                    </div>
                </span>

                <span>
                    <label for="category_link"><strong>Category Link</strong><span
                            class="text-sm text-gray-500">(optional)</span>:</label>
                    <div class="relative">
                        <div id="selected-category-link"
                            class="border border-gray-300 rounded p-1 w-full cursor-pointer flex justify-between items-center">
                            <span id="selected-category-link-text">
                                {{ old('category_link')
                                    ? str_repeat('--', $categories->firstWhere('id', old('category_link'))->level ?? 0) .
                                        ' ' .
                                        $categories->firstWhere('id', old('category_link'))->name
                                    : (isset($category) && $category->category_link
                                        ? str_repeat('--', $categories->firstWhere('id', $category->category_link)->level ?? 0) .
                                            ' ' .
                                            $categories->firstWhere('id', $category->category_link)->name
                                        : 'No category selected') }}
                            </span>

                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div id="category-link-list"
                            class="border border-gray-300 rounded p-1 w-full absolute bg-white z-10 max-h-56 overflow-auto"
                            style="display: none;">
                            <input type="text" id="category-link-search"
                                class="border border-gray-300 rounded p-1 w-full mb-1"
                                placeholder="Search Categories">
                            <div class="option" data-value="" >No Category</div>
                            @foreach ($categories as $parentCategory)
                                @if ($parentCategory->parent_id === null)
                                    @include('components.categories-dropdown', [
                                        'categories' => collect([$parentCategory]),
                                        'level' => 0,
                                        'selectedCategoryId' => old('category_link', $category->parent_id ?? ''),
                                    ])
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="category_link" id="category_link"
                            value="{{ old('category_link', isset($category) ? $category->category_link : '') }}">
                    </div>
                </span>

                <span>
                    <label for="end_date" class="text-lg font-semibold">Put on Highlight?</label>
                    <div class="flex items-center">
                        <span>
                            <input type="radio" id="put_on_highlight" name="put_on_highlight" value="1"
                                {{ old('put_on_highlight', isset($category) ? $category->put_on_highlight : '') == 1 ? 'checked' : '' }}>
                            <label for="put_on_highlight" class="ml-2">Yes</label>
                        </span>
                        <span>
                            <input type="radio" id="put_on_highlight" name="put_on_highlight" value="0"
                                class="ml-4"
                                {{ old('put_on_highlight', isset($category) ? $category->put_on_highlight : '') == 0 ? 'checked' : '' }}>
                            <label for="put_on_highlight" class="ml-2">No</label>
                        </span>
                    </div>
                </span>

                <!-- Image -->
                <div class="flex flex-col items-center justify-center">
                    <label for="image" class="font-semibold"><strong>Image:</strong></label>
                    <div class="w-[30rem] flex items-center justify-center relative">
                        <input type="file" id="image" name="image" class="hidden"
                            accept="image/png, image/gif, image/jpeg">
                        <div id="upload-container"
                            class="mt-2 flex items-center justify-center w-[20rem] h-[30rem] border border-gray-300 rounded-lg cursor-pointer relative">
                            @if (isset($category))
                                <img id="image-preview"
                                    src="{{ $category->getFirstMediaUrl('category', 'preview') ? $category->getFirstMediaUrl('category', 'preview') : '' }}"
                                    name="image" alt="Image Preview" class="object-cover w-full h-full rounded-lg">
                            @else
                                <img id="image-preview" src="" name="image" alt="Image Preview"
                                    class="object-cover w-full h-full rounded-lg">
                            @endif
                            <span id="change-text"
                                class="absolute inset-0 flex items-center justify-center text-white text-lg font-semibold bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity">Change</span>
                        </div>
                    </div>
                    <button type="button" id="delete-image" class="text-red-500">
                        Remove Image
                    </button>
                    <input type="hidden" id="remove-image" name="remove_image" value="0">
                </div>

                @if (isset($category))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $category->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($category->created_by) ? route('admin.user-management.view-user-details', $category->admin_create->id) : '#' }}">
                                    {{ isset($category->created_by) ? $category->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $category->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($category->updated_by) ? route('admin.user-management.view-user-details', $category->admin_update->id) : '#' }}">
                                    {{ isset($category->updated_by) ? $category->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif

                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save Category</button>
                </span>
            </form>
        </section>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const imageInput = document.getElementById('image');
        const uploadContainer = document.getElementById('upload-container');
        const deleteButton = document.getElementById('delete-image');
        const imagePreview = document.getElementById('image-preview');
        const removeImageInput = document.getElementById('remove-image');

        uploadContainer.addEventListener('click', () => {
            imageInput.click();
        });

        imageInput.addEventListener('change', (event) => {
            const reader = new FileReader();
            reader.onload = function() {
                imagePreview.src = reader.result;
                document.getElementById('change-text').style.opacity =
                    0.5; // Ensure 'Change' text is visible on hover
                removeImageInput.value = '0'; // Reset the hidden input to cancel remove image
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent triggering the file input click 
            imagePreview.src = '';
            imageInput.value = ''; // Clear the file input 
            removeImageInput.value = '1'; // Set the hidden input to indicate image removal 
        });

        /* Dropdown search */
        // Parent Category Dropdown
        const searchInput = document.getElementById('category-search');
        const categoryList = document.getElementById('category-list');
        const selectedCategory = document.getElementById('selected-category');
        const selectedCategoryText = document.getElementById('selected-category-text');
        const options = categoryList.querySelectorAll('.option');
        const hiddenInput = document.getElementById('parent_id');

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

        // Category Link Dropdown
        const searchLinkInput = document.getElementById('category-link-search');
        const categoryLinkList = document.getElementById('category-link-list');
        const selectedCategoryLink = document.getElementById('selected-category-link');
        const selectedCategoryLinkText = document.getElementById('selected-category-link-text');
        const linkOptions = categoryLinkList.querySelectorAll('.option');
        const hiddenLinkInput = document.getElementById('category_link');

        selectedCategoryLink.addEventListener('click', function() {
            categoryLinkList.style.display = categoryLinkList.style.display === 'none' ? 'block' :
                'none';
        });

        searchLinkInput.addEventListener('input', function() {
            const query = searchLinkInput.value.toLowerCase();
            linkOptions.forEach(function(option) {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(query) ? '' : 'none';
            });
        });

        document.addEventListener('click', function(event) {
            if (!selectedCategoryLink.contains(event.target) && !categoryLinkList.contains(event
                    .target)) {
                categoryLinkList.style.display = 'none';
            }
        });

        linkOptions.forEach(function(option) {
            option.addEventListener('click', function() {
                selectedCategoryLinkText.textContent = option.textContent.trim();
                hiddenLinkInput.value = option.dataset.value;
                linkOptions.forEach(function(opt) {
                    opt.classList.remove('selected');
                });
                option.classList.add('selected');
                categoryLinkList.style.display = 'none';
            });

            option.addEventListener('mouseover', function() {
                option.style.backgroundColor = '#f0f0f0';
            });

            option.addEventListener('mouseout', function() {
                option.style.backgroundColor = '';
            });
        });

        // Check for default selected link option
        const selectedLinkOption = categoryLinkList.querySelector('.option[data-selected="true"]');
        if (selectedLinkOption) {
            selectedCategoryLinkText.textContent = selectedLinkOption.textContent.trim();
            hiddenLinkInput.value = selectedLinkOption.dataset.value;
            selectedLinkOption.classList.add('selected');
        }
    });
</script>

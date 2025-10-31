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
                    <a href="{{ route('admin.brand-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Brand Management
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
                    <a href="{{ isset($brand) ? route('admin.brand-management.view-brand-details', $brand->id) : route('admin.brand-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        {{ isset($brand) ? 'Edit Brand' : 'Add New Brand' }}
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ isset($brand) ? __('Edit Brand') : __('Add Brand') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ isset($brand) ? __('Edit the brand details here') : __('Add a new brand here') }}
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

        <!-- Brand details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">Brand Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST"
                action="{{ isset($brand) ? route('admin.brand-management.update', $brand->id) : route('admin.brand-management.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($brand))
                    @method('PUT')
                @endif

                <span>
                    <label for="name" class="text-lg font-semibold">Name</label>
                    <input type="text" name="name" id="brand-name"
                        value="{{ old('name', isset($brand) ? $brand->name : '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter brand name">
                </span>

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
                                    : (isset($brand) && $brand->category_id
                                        ? str_repeat('--', $categories->firstWhere('id', $brand->category_id)->level ?? 0) .
                                            ' ' .
                                            $categories->firstWhere('id', $brand->category_id)->name
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
                            <div class="option" data-value="" >No Category</div>
                            @foreach ($categories as $parentCategory)
                                @if ($parentCategory->parent_id === null)
                                    @include('components.categories-dropdown', [
                                        'categories' => collect([$parentCategory]),
                                        'level' => 0,
                                        'selectedCategoryId' => old('category_id', $brand->category_id ?? ''),
                                    ])
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="category_id" id="category_id"
                            value="{{ old('category_id', isset($brand) ? $brand->category_id : '') }}">
                    </div>
                </span>

                <span>
                    <label for="name" class="text-lg font-semibold">Description</label>
                    <textarea name="description" id="brand-description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter brand description">{{ old('description', isset($brand) ? $brand->description : '') }}</textarea>
                </span>


                <!-- Image -->
                <div class="flex flex-row gap-5">
                    <label for="image" class="font-semibold"><strong>Logo</strong></label>
                    <div class="w-[20rem] flex flex-col justify-center items-center">
                        <input type="file" id="image" name="image" class="hidden" accept="image/*">
                        <div id="upload-container"
                            class="flex items-center justify-center w-64 h-64 border border-gray-300 rounded-lg cursor-pointer relative">
                            @if (isset($brand))
                                <img id="image-preview"
                                    src="{{ $brand->getFirstMediaUrl('brands', 'preview') ? $brand->getFirstMediaUrl('brands', 'preview') : '' }}"
                                    name="image" alt="Image Preview"
                                    class="w-full h-full rounded-lg object-scale-down">
                            @else
                                <img id="image-preview" src="" name="image" alt="Image Preview"
                                    class="object-cover w-full h-full rounded-lg">
                            @endif
                            <span id="change-text"
                                class="absolute inset-0 flex items-center justify-center text-white text-lg font-semibold bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity">Change</span>
                        </div>
                        <button type="button" id="delete-image" class="text-red-500">
                            Remove Image
                        </button>
                    </div>
                    <input type="hidden" id="remove-image" name="remove_image" value="0">
                </div>

                @if (isset($brand))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $brand->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($brand->created_by) ? route('admin.user-management.view-user-details', $brand->admin_create->id) : '#' }}">
                                    {{ isset($brand->created_by) ? $brand->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $brand->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($brand->updated_by) ? route('admin.user-management.view-user-details', $brand->admin_update->id) : '#' }}">
                                    {{ isset($brand->updated_by) ? $brand->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif

                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save Brand
                    </button>
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
        const removeImageInput = document.getElementById('remove-image');
        const imagePreview = document.getElementById('image-preview');

        uploadContainer.addEventListener('click', () => {
            imageInput.click();
        });

        imageInput.addEventListener('change', (event) => {
            const reader = new FileReader();
            reader.onload = function() {
                imagePreview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent triggering the file input click 
            imagePreview.src = '';
            imageInput.value = ''; // Clear the file input 
            removeImageInput.value = '1'; // Set the hidden input to indicate image removal 
        });

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
    });
</script>

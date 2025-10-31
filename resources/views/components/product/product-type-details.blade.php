@props(['productid', 'type'])

<section class="flex flex-row gap-5 md:gap-10 border border-gray-400 rounded-lg p-5">
    <form id="product-details" class="w-full" method="POST" enctype="multipart/form-data"
        action="{{ $type ? route('admin.product-management.update-product-type', $type->id) : route('admin.product-management.store-product-type') }}">
        @csrf
        @if ($type)
            @method('PUT')
        @endif

        <input type="hidden" name="product_id" value="{{ $productid }}">

        <div class="grid grid-cols-2 w-full gap-4">
            <div>
                <label for="name"><strong>SKU:</strong></label>
                <input type="text" id="name" name="sku" value="{{ old('sku', $type ? $type->sku : '') }}"
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Stock Keeping Unit.</p>
            </div>
            <div>
                <label for="name"><strong>Type Name:</strong></label>
                <input type="text" id="name" name="name" value="{{ old('name', $type ? $type->name : '') }}"
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Nama tipe/varian. Contoh: 30x30 putih</p>
            </div>
            <div>
                <label for="name"><strong>Color:</strong></label>
                <input type="color" id="color" name="color" value="{{ old('color', $type ? $type->color : '') }}"
                    class="rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Warna</p>
            </div>
            <div>
                <label for="price"><strong>Price:</strong></label>
                <input type="number" id="price" name="price" min="0" step="1"
                    value="{{ old('price', $type ? $type->price : '') }}" class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Harga - Rp</p>

            </div>
            <div class="" id="fixed-discount-container">
                <label for="discount_fixed"><strong>Fixed Discount:</strong></label>
                <input type="number" id="discount_fixed" min="0" step="10" name="discount_fixed"
                value="{{ old('discount_fixed', $type ? $type->discount_fixed : 0) }}" class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Diskon - Rp</p>
            </div>
            <div>
                <label for="discount_percentage"><strong>Percentage Discount:</strong></label>
                <input type="number" id="discount_percentage" min="0" max="100" step="0.01"
                name="discount_percentage" value="{{ old('discount_percentage', $type ? $type->discount_percentage : '') }}"
                    class="border border-gray-300 rounded p-1 w-full">
                    <p class="text-gray-400 text-sm">Diskon - persen</p>
            </div>
            <div class="col-span-2">
                <label for="description"><strong>Description:</strong></label>
                <textarea id="description" name="description" class="border border-gray-300 rounded p-1 w-full">{{ old('description', $type ? $type->description : '') }}</textarea>
            </div>
            <div class="">
                <label for="weight"><strong>Weight:</strong></label>
                <input type="number" id="weight" name="weight" min="0" step="0.01"
                    value="{{ old('weight', $type ? $type->weight : '') }}" class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Berat - g</p>
            </div>
            <div>
                <label for="stock"><strong>Stock:</strong></label>
                <input type="number" id="stock" name="stock" min="0"
                    value="{{ old('stock', $type ? $type->stock : '') }}" class="border border-gray-300 rounded p-1 w-full">
            </div>
            <div>
                <label hidden for="total_sales"><strong>Total Sales:</strong></label>
                <input hidden type="number" id="total_sales" name="total_sales" min="0"
                    value="{{ old('total_sales', $type ? $type->total_sales : '') }}"
                    class="border border-gray-300 bg-gray-200 rounded p-1 w-full" disabled>
            </div>

            <div class="image-upload-container col-span-2">
                <label for="images"><strong>Images:</strong></label>
                <div class="flex flex-col gap-2">
                    <div id="existing-image-container-{{ $type->id ?? 'new' }}" class="flex gap-3 flex-wrap">
                        @if ($type && $type->getMedia('*')->count() > 0)
                            @foreach ($type->getMedia('*') as $media)
                                <div class="relative group existing-image">
                                    <img src="{{ $media->getUrl('preview') }}" alt="Image"
                                        class="h-24 w-24 object-scale-down border border-gray-200">
                                    <button type="button" data-media-id="{{ $media->id }}"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-2 py-1 text-xs delete-image-btn hidden group-hover:block">x</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="new-image-container-{{ $type->id ?? 'new' }}" class="flex gap-3 flex-wrap">
                        <label
                            class="h-24 w-24 flex items-center justify-center border border-gray-300 rounded cursor-pointer">
                            <span class="text-gray-500">+</span>
                            <input type="file" id="images-{{ $type->id ?? 'new' }}" name="images[]" multiple
                                class="hidden"
                                onchange="window.previewMultipleImages(event, '{{ $type->id ?? 'new' }}')"> </label>
                    </div>
                </div>
            </div>
            @if (isset($type))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $type->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($type->created_by) ? route('admin.user-management.view-user-details', $type->admin_create->id) : '#' }}">
                                    {{ isset($type->created_by) ? $type->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $type->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($type->updated_by) ? route('admin.user-management.view-user-details', $type->admin_update->id) : '#' }}">
                                    {{ isset($type->updated_by) ? $type->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif
        </div>
        <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
    </form>
    @if (isset($type))
        <form action="{{ route('admin.product-management.delete-product-type', $type->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="mt-3 px-4 py-2 bg-red-500 text-white rounded">Delete Product Type</button>
        </form>
    @endif

</section>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const selectedFilesMap = new Map();

        window.previewMultipleImages = function(event, typeId) {
            if (!selectedFilesMap.has(typeId)) {
                selectedFilesMap.set(typeId, []);
            }
            const selectedFiles = selectedFilesMap.get(typeId);
            const files = Array.from(event.target.files);

            // Add new files to the selectedFiles array
            files.forEach(file => {
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });

            const container = document.getElementById(`new-image-container-${typeId}`);
            if (!container) return;

            container.innerHTML = ''; // Clear the new images container

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrapper = document.createElement("div");
                    imgWrapper.className = "relative group image-preview";
                    imgWrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Image" class="h-24 w-24 object-scale-down">
                        <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-2 py-1 text-xs delete-image-btn hidden group-hover:block" data-index="${index}" data-type-id="${typeId}">x</button>
                    `;
                    imgWrapper.querySelector(".delete-image-btn").addEventListener("click", (
                        event) => {
                        const index = event.target.dataset.index;
                        const typeId = event.target.dataset.typeId;
                        selectedFiles.splice(index, 1);
                        window.previewMultipleImages({
                            target: {
                                files: selectedFiles
                            }
                        }, typeId);
                    });
                    container.appendChild(imgWrapper);
                };
                reader.readAsDataURL(file);
            });

            // Add the "+" button at the end
            const addButtonWrapper = document.createElement("label");
            addButtonWrapper.className =
                "h-24 w-24 flex items-center justify-center border border-gray-300 rounded cursor-pointer";
            addButtonWrapper.innerHTML = `
                <span class="text-gray-500">+</span>
                <input type="file" id="images-${typeId}" name="images[]" multiple class="hidden" onchange="window.previewMultipleImages(event, '${typeId}')">
            `;
            container.appendChild(addButtonWrapper);

            // Update the file input
            const input = document.getElementById(`images-${typeId}`);
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        };

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

        // Function to initialize existing images 
        function initializeExistingImages(typeId, mediaUrls) {
            if (!selectedFilesMap.has(typeId)) {
                selectedFilesMap.set(typeId, []);
            }
            const selectedFiles = selectedFilesMap.get(typeId);
            mediaUrls.forEach(url => {
                const imgWrapper = document.createElement("div");
                imgWrapper.className = "relative group existing-image";
                imgWrapper.innerHTML =
                    ` <img src="${url}" alt="Image" class="h-24 w-24 object-scale-down"> <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-2 py-1 text-xs delete-image-btn hidden group-hover:block" data-url="${url}" data-type-id="${typeId}">x</button> `;
                imgWrapper.querySelector(".delete-image-btn").addEventListener("click", (event) => {
                    const url = event.target.dataset.url;
                    const typeId = event.target.dataset.typeId;
                    selectedFiles.splice(selectedFiles.findIndex(file => file.url === url), 1);
                    imgWrapper.remove();
                });
                const container = document.getElementById(`existing-image-container-${typeId}`);
                container.appendChild(imgWrapper);
            });
        }

        // Initialize existing forms 
        document.querySelectorAll('.existing-product-type-form').forEach(form => {
            const typeId = form.dataset.typeId;
            if (!selectedFilesMap.has(typeId)) {
                selectedFilesMap.set(typeId, []);
            }
            // Fetch existing media URLs 
            const mediaUrls = Array.from(form.querySelectorAll('img')).map(img => img.src);
            initializeExistingImages(typeId, mediaUrls);
        });

        document.addEventListener('change', function(event) {
            if (event.target.matches('input[type="file"]')) {
                const typeId = event.target.id.split('-')[1];
                if (typeId != 'new') {
                    window.previewMultipleImages(event, typeId);
                }
            }
        });

        document.querySelectorAll(".delete-image-btn").forEach((btn) => {
            btn.addEventListener("click", function() {
                const mediaId = this.dataset.mediaId;
                const parent = this.parentElement;
                fetch(`{{ route('admin.product-management.delete-media', '') }}/${mediaId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]').content,
                    },
                }).then((res) => {
                    if (res.ok) parent.remove();
                    else {}
                });
            });
        });
    });
</script>

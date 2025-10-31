@props(['productid'])

<section class="flex flex-row gap-5 md:gap-10 border border-gray-400 rounded-lg p-5">
    <form id="new-product-details" class="w-full" method="POST" enctype="multipart/form-data"
        action="{{ route('admin.product-management.store-product-type') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $productid }}">

        <div class="grid grid-cols-2 w-full gap-4">
            <div>
                <label for="name"><strong>SKU:</strong></label>
                <input type="text" id="name" name="sku" value=""
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Stock Keeping Unit.</p>
            </div>
            <div>
                <label for="name"><strong>Type Name:</strong></label>
                <input type="text" id="name" name="name" value=""
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Nama tipe/varian. Contoh: 30x30 putih</p>
            </div>
            <div>
                <label for="name"><strong>Color:</strong></label>
                <input type="color" id="color" name="color" value="" class="rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Warna</p>
            </div>
            <div>
                <label for="price"><strong>Price:</strong></label>
                <input type="number" id="price" name="price" min="0" step="1" value=""
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Harga - Rp</p>
            </div>
            <div class="">
                <label for="discount_fixed"><strong>Fixed Discount:</strong></label>
                <input type="number" id="discount_fixed" min="0" step="10" name="discount_fixed"
                value="0" class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Diskon - Rp</p>
            </div>
            <div>
                <label for="discount_percentage"><strong>Percentage Discount:</strong></label>
                <input type="number" id="discount_percentage" min="0" max="100" step="0.01"
                name="discount_percentage" value="0" class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Diskon - persen</p>
            </div>
            <div class="">
                <label for="weight"><strong>Weight:</strong></label>
                <input type="number" id="weight" name="weight" min="0" step="0.01" value=""
                    class="border border-gray-300 rounded p-1 w-full">
                <p class="text-gray-400 text-sm">Berat - g</p>
            </div>
            <div>
                <label for="stock"><strong>Stock:</strong></label>
                <input type="number" id="stock" name="stock" min="0" value=""
                    class="border border-gray-300 rounded p-1 w-full">
            </div>
            <div class="col-span-2">
                <label for="description"><strong>Description:</strong></label>
                <textarea id="description" name="description" class="border border-gray-300 rounded p-1 w-full"></textarea>
            </div>
            <div class="image-upload-container col-span-2">
                <label for="images"><strong>Images:</strong></label>
                <div id="new-image-container-new" class="flex gap-3 flex-wrap items-center">
                    <label
                        class="h-24 w-24 flex items-center justify-center border border-gray-300 rounded cursor-pointer">
                        <span class="text-gray-500">+</span>
                        <input type="file" id="images-new" name="images[]" multiple class="hidden"
                            onchange="window.previewNewProductTypeImages(event, 'new')">
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
    </form>
</section>

<script>

    const selectedFilesMapNew = new Map();

    window.previewNewProductTypeImages = function(event, typeId) {
        if (!selectedFilesMapNew.has(typeId)) {
            selectedFilesMapNew.set(typeId, []);
        }

        const selectedFiles = selectedFilesMapNew.get(typeId);
        const files = Array.from(event.target.files);

        files.forEach(file => {
            if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });

        const container = document.getElementById(`new-image-container-${typeId}`);
        if (!container) return;

        container.innerHTML = ''; // Clear the previous previews

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgWrapper = document.createElement("div");
                imgWrapper.className = "relative group image-preview";
                imgWrapper.innerHTML = `
                <img src="${e.target.result}" alt="Image" class="h-24 w-24 object-scale-down">
                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-2 py-1 text-xs delete-image-btn hidden group-hover:block" data-index="${index}" data-type-id="${typeId}">x</button>
            `;
                imgWrapper.querySelector(".delete-image-btn").addEventListener("click", (event) => {
                    const index = event.target.dataset.index;
                    selectedFiles.splice(index, 1);
                    previewNewProductTypeImages({
                        target: {
                            files: selectedFiles
                        }
                    }, typeId);
                });
                container.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });

        const addButtonWrapper = document.createElement("label");
        addButtonWrapper.className =
            "h-24 w-24 flex items-center justify-center border border-gray-300 rounded cursor-pointer";
        addButtonWrapper.innerHTML = `
        <span class="text-gray-500">+</span>
        <input type="file" id="images-new-${typeId}" name="images[]" multiple class="hidden" onchange="previewNewProductTypeImages(event, '${typeId}')">
    `;
        container.appendChild(addButtonWrapper);

        const input = document.getElementById(`images-new-${typeId}`);
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    };
</script>

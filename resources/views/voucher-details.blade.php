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
                    <a href="{{ route('admin.voucher-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Voucher Management
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
                    <a href="{{ route('admin.voucher-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Voucher
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
                    <a href="{{ isset($voucher) ? route('admin.voucher-management.view-voucher-details', $voucher->id) : route('admin.voucher-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        {{ isset($voucher) ? 'Edit Voucher' : 'Add Voucher' }}
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ isset($voucher) ? __('Update Voucher') : __('Add Voucher') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ isset($voucher) ? __('Update a voucher here') : __('Add a new voucher here') }}
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
        <!-- Voucher details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">Voucher Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST" enctype="multipart/form-data" id="s"
                action="{{ isset($voucher) ? route('admin.voucher-management.update', $voucher->id) : route('admin.voucher-management.store') }}">
                @csrf
                @if (isset($voucher))
                    @method('PUT')
                @endif

                <span>
                    <label for="name" class="text-lg font-semibold">Voucher Name</label>
                    <input type="text" name="name" id="voucher-name"
                        value="{{ old('name', $voucher->name ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter voucher name">
                    <p class="text-sm text-gray-400">Nama voucher</p>
                </span>

                <span>
                    <label for="code" class="text-lg font-semibold">Voucher Code</label>
                    <input type="text" name="code" id="voucher-code"
                        value="{{ old('code', $voucher->code ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter voucher code">
                    <p class="text-sm text-gray-400">Kode voucher</p>
                </span>

                <span>
                    <label for="code" class="text-lg font-semibold">Voucher Type</label>
                    <select name="type" id="voucher-type" class="w-full border border-gray-300 rounded-lg p-2">
                        <option disabled {{ old('type', isset($voucher) && $voucher->type ? '' : 'selected') }}>Select
                            voucher type</option>
                        <option value="ongkir"
                            {{ old('type', isset($voucher) && $voucher->type == 'ongkir' ? 'ongkir' : '') == 'ongkir' ? 'selected' : '' }}>
                            Ongkir
                        </option>
                        <option value="transaction_item"
                            {{ old('type', isset($voucher) && $voucher->type == 'transaction_item' ? 'transaction_item' : '') == 'transaction_item' ? 'selected' : '' }}>
                            Diskon Produk
                        </option>
                    </select>
                    <p class="text-sm text-gray-400">Tipe voucher</p>
                </span>


                <div class="p-3 border border-gray-300 rounded-lg">
                    <span>
                        <label for="value_percentage" class="text-lg font-semibold">Discount in Percentage (%)</label>
                        <input type="number" min="0" max="100" name="value_percentage"
                            id="value_percentage"
                            value="{{ old('value_percentage', $voucher->value_percentage ?? 0) }}"
                            class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter value (%)">
                    </span>

                    <span>
                        <label for="value_fixed" class="text-lg font-semibold">Discount in Fixed Value (Rp)</label>
                        <input type="number" min="0" name="value_fixed" id="value_fixed"
                            value="{{ old('value_fixed', $voucher->value_fixed ?? 0) }}"
                            class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter value (Rp)">
                    </span>
                    <span class="text-sm text-gray-400">
                        <p class="pt-2">Apabila kedua tipe diskon di-isi, maka Fixed Discount akan berperan sebagai
                            pembatas
                            Percentage
                            Discount.</p>
                        <p>Contoh: Diskon 20% dengan nilai maksimum sebesar Rp50.000</p>
                    </span>
                </div>

                <span>
                    <label for="minimum_transaction_value" class="text-lg font-semibold">Minimum Transaction</label>
                    <input type="number" min="0" name="minimum_transaction_value"
                        id="minimum_transaction_value"
                        value="{{ old('minimum_transaction_value', $voucher->minimum_transaction_value ?? 0) }}"
                        class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter minimum transaction value (Rp)">
                    <p class="text-sm text-gray-400">Nilai minimum transaksi yang harus dicapai agar voucher dapat
                        digunakan
                    </p>
                </span>

                <span>
                    <label for="quantity" class="text-lg font-semibold">Initial Quota</label>
                    <input type="number" min="0" name="quantity" id="quantity"
                        value="{{ old('quantity', $voucher->quantity ?? 0) }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter quantity">
                    <p class="text-sm text-gray-400">Kuota voucher yang disediakan</p>
                </span>
                <span class="{{ !isset($voucher) ? 'hidden' : '' }}">
                    <label for="used" class="text-lg font-semibold">Used</label>
                    <input type="text" min="0" name="used" id="used"
                        value="{{ old('used', $voucher->used ?? '') }}"
                        class="w-full bg-gray-100 border border-gray-300 rounded-lg p-2" disabled>
                    <p class="text-sm text-gray-400">Kuota voucher yang telah digunakan</p>
                </span>
                <span class="{{ !isset($voucher) ? 'hidden' : '' }}">
                    <label for="quota" class="text-lg font-semibold">Current Quota</label>
                    <input type="text" min="0" name="quota" id="quota"
                        value="{{ isset($voucher) ? $voucher->quantity - $voucher->used : '' }}"
                        class="w-full bg-gray-100 border border-gray-300 rounded-lg p-2" disabled>
                    <p class="text-sm text-gray-400">Sisa kuota voucher yang tersedia</p>
                </span>

                <span>
                    <label for="start_date" class="text-lg font-semibold">Start Date</label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ \Carbon\Carbon::parse(old('start_date', $voucher->start_date ?? ''))->format('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter start date">
                    <p class="text-sm text-gray-400">Tanggal dimulainya voucher berlaku</p>
                </span>
                <span>
                    <label for="end_date" class="text-lg font-semibold">End Date</label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ \Carbon\Carbon::parse(old('end_date', $voucher->end_date ?? ''))->format('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter end date">
                    <p class="text-sm text-gray-400">Tanggal berakhirnya voucher berlaku</p>
                </span>

                <span>
                    <label for="description" class="text-lg font-semibold">Description</label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter description">{{ old('description', $voucher->description ?? '') }}</textarea>
                    <p class="text-sm text-gray-400">Deskripsi voucher</p>
                </span>

                <span>
                    <label for="terms" class="text-lg font-semibold">Terms and Conditions</label>
                    <x-rich-text-input name="terms" label="terms" :initialValue="old('terms', $voucher->terms ?? '')" />
                    <p class="text-sm text-gray-400">Syarat dan ketentuan voucher</p>
                </span>

                <span>
                    <label for="is_active" class="text-lg font-semibold">Valid at?</label>
                    @if (isset($voucher))
                        <div id="existing-cities-container" class="grid grid-cols-6 gap-2">
                            @if ($voucher->voucher_city_requirements)
                                @foreach ($voucher->voucher_city_requirements as $cityRequirement)
                                    <div class="bg-blue-100 p-2 rounded shadow-sm selected-city flex items-center justify-between"
                                        data-city="{{ $cityRequirement->city }}">
                                        {{ $cityRequirement->city }}
                                        <button type="button"
                                            class="remove-city-button text-red-300 hover:text-red-500 ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    <!-- Newly added cities -->
                    <div id="selected-cities" class="flex flex-wrap items-center gap-2 pt-2"></div>

                    <div class="mt-4">
                        <x-input-label for="province" :value="__('Province')" />
                        <select id="province" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option disabled selected>Select Province</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="city" :value="__('City')" />
                        <select id="city" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option disabled selected>Select City</option>
                        </select>
                    </div>

                    <p class="text-sm text-gray-400">Di mana saja voucher berlaku?
                    </p>
                    <button type="button" id="add-city-button"
                        class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add
                        City</button>

                    <input type="hidden" name="selected_cities" id="selected-cities-input"
                        value="{{ old('selected_cities') }}">
                </span>

                <span>
                    <label for="end_date" class="text-lg font-semibold">One time use per customer?</label>
                    <div class="flex items-center">
                        <span>
                            <input type="radio" id="is_one_time_use_yes" name="is_one_time_use" value="1"
                                {{ old('is_one_time_use', isset($voucher) && $voucher->is_one_time_use ? '1' : '') == '1' ? 'checked' : '' }}>
                            <label for="is_one_time_use_yes" class="ml-2">Yes</label>
                        </span>
                        <span>
                            <input type="radio" id="is_one_time_use_no" name="is_one_time_use" value="0"
                                class="ml-4"
                                {{ old('is_one_time_use', isset($voucher) && !$voucher->is_one_time_use ? '0' : '') == '0' ? 'checked' : '' }}>
                            <label for="is_one_time_use_no" class="ml-2">No</label>
                        </span>
                    </div>
                    <p class="text-sm text-gray-400">Apakah voucher hanya dapat digunakan sekali oleh setiap pelanggan?
                    </p>
                </span>


                <!-- Image -->
                <div class="flex flex-col items-center justify-center">
                    <label for="image" class="font-semibold"><strong>Image:</strong></label>
                    <div class="w-[30rem] flex items-center justify-center relative">
                        <input type="file" id="image" name="image" class="hidden" accept="image/*">
                        <div id="upload-container"
                            class="mt-2 flex items-center justify-center w-[30rem] h-[20rem] border border-gray-300 rounded-lg cursor-pointer relative">
                            @if (isset($voucher))
                                <img id="image-preview"
                                    src="{{ $voucher->getFirstMediaUrl('voucher', 'preview') ? $voucher->getFirstMediaUrl('voucher', 'preview') : '' }}"
                                    name="image" alt="Image Preview"
                                    class="w-full h-full rounded-lg object-scale-down">
                            @else
                                <img id="image-preview" src="" name="image" alt="Image Preview"
                                    class="w-full h-full rounded-lg object-scale-down">
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

                @if (isset($voucher))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $voucher->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($voucher->created_by) ? route('admin.user-management.view-user-details', $voucher->admin_create->id) : '#' }}">
                                    {{ isset($voucher->created_by) ? $voucher->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $voucher->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($voucher->updated_by) ? route('admin.user-management.view-user-details', $voucher->admin_update->id) : '#' }}">
                                    {{ isset($voucher->updated_by) ? $voucher->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif
                </span>
                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save Voucher</button>
                </span>
            </form>
        </section>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceDropdown = document.getElementById('province');
        const cityDropdown = document.getElementById('city');
        const selectedCitiesContainer = document.getElementById('selected-cities');
        const selectedCitiesInput = document.getElementById('selected-cities-input');
        const addCityButton = document.getElementById('add-city-button');
        const existingCitiesContainer = document.getElementById('existing-cities-container');

        // Add existing cities to the selected cities input
        if (existingCitiesContainer) {
            const existingCities = [];
            existingCitiesContainer.querySelectorAll('.selected-city').forEach(cityElement => {
                const cityName = cityElement.getAttribute('data-city');
                existingCities.push(cityName);
            });
            selectedCitiesInput.value = existingCities.join(',');
        }
        const cities = @json($cities); // All cities data passed from the controller

        // Populate city dropdown based on selected province
        function populateCityDropdown(provinceId) {
            cityDropdown.innerHTML = '<option disabled selected>Select City</option>';
            const filteredCities = cities.filter(city => city.id.startsWith(provinceId));
            filteredCities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                cityDropdown.appendChild(option);
            });
        }

        // Handle province selection change
        provinceDropdown.addEventListener('change', function() {
            const selectedProvinceId = this.value;
            populateCityDropdown(selectedProvinceId);
        });

        // Add new city to the list
        addCityButton.addEventListener('click', function() {
            const cityName = cityDropdown.value;
            if (cityName && !selectedCitiesInput.value.includes(cityName) && cityName !==
                'Select City') {
                addCityElement(cityName, selectedCitiesContainer, selectedCitiesInput);
            }
        });

        // Add a city element to the DOM
        function addCityElement(cityName, container, input) {
            const cityElement = document.createElement('div');
            cityElement.classList.add(
                'selected-city',
                'bg-gray-200',
                'p-2',
                'rounded',
                'shadow-sm',
                'flex',
                'flex-row',
                'items-center',
                'justify-between'
            );
            cityElement.textContent = cityName;
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('remove-city-button', 'text-red-500', 'hover:text-red-700', 'ml-2');
            // removeButton.textContent = '';
            removeButton.innerHTML = `
                <button type="button" class="remove-city-button text-red-500 hover:text-red-700 ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" 
                        class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </button>`;

            cityElement.appendChild(removeButton);
            container.appendChild(cityElement);

            const currentCities = input.value ? input.value.split(',') : [];
            currentCities.push(cityName);
            input.value = currentCities.join(',');

            // Attach remove event to the button
            removeButton.addEventListener('click', function() {
                cityElement.remove();
                const updatedCities = input.value.split(',').filter(city => city !== cityName);
                input.value = updatedCities.join(',');
            });
        }

        // Attach remove functionality to existing cities
        existingCitiesContainer.querySelectorAll('.remove-city-button').forEach(button => {
            button.addEventListener('click', function() {
                const cityElement = this.parentElement;
                const cityName = cityElement.getAttribute('data-city');
                cityElement.remove();
                const updatedCities = selectedCitiesInput.value.split(',').filter(city =>
                    city !== cityName);
                selectedCitiesInput.value = updatedCities.join(',');
            });
        });

    });
</script>

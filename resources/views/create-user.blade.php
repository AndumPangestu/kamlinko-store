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
                    <a href="{{ route('admin.user-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        User Management
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
                    <a href="{{ route('admin.user-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Add New User
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Add User') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('Add a new ' . $mode . ' here') }}
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

        <!-- Form -->
        <section class="px-12">
            <form method="POST" class="border rounded-lg p-12"
                action="{{ route('admin.customer-management.store', ['mode' => $mode]) }}">
                @csrf
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Username -->
                <div class="mt-4">
                    <x-input-label for="username" :value="__('User Name')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                        :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="mt-4">
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input id="address" class="block mt-1 w-full" name="address"
                        required>{{ old('address') }}</x-text-input>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="province" :value="__('Province')" />
                    <select name="province" id="province"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option disabled selected>Select Province</option>
                        @foreach ($provinces as $province)
                            <option id="{{ $province['id'] }}" value="{{ $province['name'] }}">{{ $province['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="city" :value="__('City')" />
                    <select name="city" id="city"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option disabled selected>Select City</option>
                        <!-- Cities will be dynamically populated here -->
                    </select>
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="subdistrict" :value="__('Subdistrict')" />
                    <select name="subdistrict" id="subdistrict"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option disabled selected>Select Subdistricts</option>
                        <!-- Subdistricts will be dynamically populated here -->
                    </select>
                    <x-input-error :messages="$errors->get('subdistrict')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="zip" :value="__('Zip')" />
                    <x-text-input id="zip" class="block mt-1 w-full" name="zip"
                        required>{{ old('zip') }}</x-text-input>
                    <x-input-error :messages="$errors->get('zip')" class="mt-2" />
                </div>

                <div class="mt-4">
                    @if ($mode == 'customer')
                        <x-input-label for="latitude" :value="__('Latitude')" />
                        <x-text-input id="latitude" class="block mt-1 w-full" name="latitude"
                            required>{{ old('latitude') }}</x-text-input>
                    @else
                        <x-text-input id="latitude" class="hidden mt-1 bg-gray-200 w-full" type="text"
                            name="latitude" value="0" autofocus autocomplete="latitude" />
                    @endif
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                </div>

                <div class="mt-4">
                    @if ($mode == 'customer')
                        <x-input-label for="longitude" :value="__('Longitude')" />
                        <x-text-input id="longitude" class="block mt-1 w-full" name="longitude"
                            required>{{ old('longitude') }}</x-text-input>
                    @else
                        <x-text-input id="longitude" class="hidden mt-1 bg-gray-200 w-full" type="text"
                            name="longitude" value="0" autofocus autocomplete="longitude" />
                    @endif
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="description" :value="__('Address Description')" />
                    <x-text-input id="address_description" class="block mt-1 w-full" name="description"
                        required>{{ old('description') }}</x-text-input>
                    <x-input-error :messages="$errors->get('address_description')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    @if ($mode=='customer')
                        <x-text-input id="role" class="block mt-1 bg-gray-200 w-full" type="text"
                            name="role" value="{{ $mode }}" autofocus autocomplete="role" />
                    @elseif (auth()->user()->role == 'superadmin')
                        <select name="role" id="role"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                        </select>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-8">
                    <x-primary-button class="ms-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>

{{-- filter cities --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceDropdown = document.getElementById('province');
        const cityDropdown = document.getElementById('city');
        const subdistrictDropdown = document.getElementById('subdistrict');

        // All cities passed from the controller
        const cities = @json($cities);
        const subdistricts = @json($subdistricts);

        // Event listener for province change
        provinceDropdown.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedProvinceId = selectedOption.id;
            console.log('selectedProvinceId', selectedProvinceId);
            // Clear city dropdown
            cityDropdown.innerHTML = '<option disabled selected>Select City</option>';

            // Filter and populate cities
            const filteredCities = cities.filter(city => city.id.startsWith(selectedProvinceId + '.'));
            filteredCities.forEach(city => {
                const option = document.createElement('option');
                option.id = city.id;
                option.value = city.name;
                option.textContent = city.name;
                cityDropdown.appendChild(option);
            });
        });

        // Event listener for city change
        cityDropdown.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedCityId = selectedOption.id;
            // Clear subdistricts dropdown
            subdistrictDropdown.innerHTML = '<option disabled selected>Select Subdistricts</option>';

            // Filter and populate subdistricts
            const filteredSubdistricts = subdistricts.filter(subdistrict => subdistrict.id.startsWith(
                selectedCityId + '.'));
            filteredSubdistricts.forEach(subdistrict => {
                const option = document.createElement('option');
                option.value = subdistrict.name;
                option.textContent = subdistrict.name;
                subdistrictDropdown.appendChild(option);
            });
        });
    });
</script>

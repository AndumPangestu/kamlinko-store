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
                    <a href="{{ route('admin.user-management', ['mode' => 'customer']) }}"
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
                    @if ($mode === 'customer')
                        <a href="{{ route('admin.user-management', ['mode' => 'customer']) }}"
                            class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                            Customer
                        </a>
                    @else
                        <a href="{{ route('admin.user-management', ['mode' => 'admin']) }}"
                            class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                            Admin
                        </a>
                    @endif
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('User Management') }}
    </x-slot>
    <x-slot name="headerDesc">
        @if ($mode === 'customer')
            {{ __('Manage your customers and their account here') }}
        @elseif ($mode === 'admin')
            {{ __('Manage your admins and their account here') }}
        @endif
    </x-slot>

    <div class="py-12">
        <div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">

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

                    <!-- Conditionally show customer or admin list based on the mode parameter -->
                    <div id="user-list">
                        @if ($mode === 'customer')
                            @include('components.user.customer-list', ['customers' => $data])
                        @else
                            @include('components.user.admin-list', ['admins' => $data])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<!-- Dynamic Search -->
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const tableWrapper = document.getElementById('user-list');
        const mode = `{{ $mode }}`;
        let debounceTimer = 0;
        // Attach an event listener to the wrapper to detect input changes
        tableWrapper.addEventListener('input', function(event) {
            const inputElement = event.target;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // Check if the input is one of the search boxes
                if (inputElement && inputElement.matches('[id^=search_]')) {
                    const searchInputs = document.querySelectorAll('[id^=search_]');

                    // Capture all search parameters
                    const searchParams = Array.from(searchInputs).reduce((params, input) => {
                        if (input.value) {
                            params[input.name] = input.value;
                        }
                        return params;
                    }, {});

                    const activeElementId = inputElement.id; // Save the current input field ID
                    const cursorPosition = inputElement.selectionStart; // Save cursor position

                    // Make an AJAX request with search parameters
                    fetch(`{{ route('admin.user-management') }}?${new URLSearchParams(searchParams)}&mode=${mode}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                        .then((response) => response.text())
                        .then((data) => {
                            // Update the table content
                            tableWrapper.innerHTML = data;

                            // Restore the focus and cursor position
                            const activeInput = document.getElementById(activeElementId);
                            if (activeInput) {
                                activeInput.focus();
                                activeInput.setSelectionRange(cursorPosition,
                                    cursorPosition);
                            }
                        })
                        .catch((error) => console.error('Error fetching data:', error));
                }
            }, 500);
        });
    });
</script>

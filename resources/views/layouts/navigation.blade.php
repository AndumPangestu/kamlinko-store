<aside>
    <div class="flex flex-col min-h-screen px-4 py-8 ">
        <!-- Logo -->
        <div class="shrink-0 flex flex-col items-center ">
            <a href="{{ route('admin.dashboard') }}">
                <x-application-logo class="block h-12 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-2 mt-6">
            <!-- Dashboard -->
            <div>
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-house" viewBox="0 0 16 16">
                        <path
                            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                    </svg>
                    <span class="ps-5">
                        {{ __('Dashboard') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- User Management -->
            <div>
                <div x-data="userManagement">
                    <button x-on:click="toggle"
                        class="w-full inline-flex items-center justify-between px-5 py-5 rounded-lg text-normal font-medium leading-5 text-gray-700 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-people" viewBox="0 0 16 16">
                                <path
                                    d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                            </svg>
                            <span class="ps-5">{{ __('User Management') }}</span>
                        </div>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                            class="w-4 h-4 transition-transform duration-200 transform">
                            <path fill-rule="evenodd"
                                d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 space-y-2 pl-4 w-full">
                        <x-nav-link class="flex flex-row w-full" :href="route('admin.user-management', ['mode' => 'customer'])" :active="request()->routeIs('admin.user-management') && request()->get('mode') === 'customer'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-person" viewBox="0 0 16 16">
                                <path
                                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                            </svg>
                            <span class="ps-5">
                                {{ __('Customer') }}
                            </span>
                        </x-nav-link>

                        @if (auth()->user()->role === 'superadmin')
                            <x-nav-link class="flex flex-row w-full" :href="route('admin.user-management', ['mode' => 'admin'])" :active="request()->routeIs('admin.user-management') && request()->get('mode') === 'admin'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                                    <path
                                        d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                                </svg>
                                <span class="ps-5">
                                    {{ __('Admin') }}
                                </span>
                            </x-nav-link>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Management -->
            <div x-data="productManagement">
                <button x-on:click="toggle"
                    class="w-full inline-flex items-center justify-between px-5 py-5 rounded-lg text-normal font-medium leading-5 text-gray-700 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                    <div class="flex flex-row items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-archive" viewBox="0 0 16 16">
                            <path
                                d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                        </svg>
                        <span class="ps-5">{{ __('Product Management') }}</span>
                    </div>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="rotateClasses"
                        class="w-4 h-4 transition-transform duration-200 transform">
                        <path fill-rule="evenodd"
                            d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" class="mt-2 space-y-2 pl-4 w-full">
                    <!-- Products -->
                    <x-nav-link class="flex flex-row w-full" :href="route('admin.product-management')" :active="request()->routeIs('admin.product-management') ||
                        request()->routeIs('admin.product-management.create') ||
                        request()->routeIs('admin.product-management.view-product-details', ['mode' => 'basic-details'])">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-box-seam" viewBox="0 0 16 16">
                            <path
                                d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                        </svg>
                        <span class="ps-5">
                            {{ __('Products') }}
                        </span>
                    </x-nav-link>
                    <!-- Categories -->
                    <x-nav-link class="flex flex-row w-full" :href="route('admin.product-category-management')" :active="request()->routeIs('admin.product-category-management') ||
                        request()->routeIs('admin.product-category-management.create') ||
                        request()->routeIs('admin.product-category-management.view-category-details')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-columns-gap" viewBox="0 0 16 16">
                            <path
                                d="M6 1v3H1V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm14 12v3h-5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM6 8v7H1V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zm14-6v7h-5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z" />
                        </svg>
                        <span class="ps-5">
                            {{ __('Categories') }}
                        </span>
                    </x-nav-link>
                    <!-- Tags -->
                    <x-nav-link class="flex flex-row w-full" :href="route('admin.product-tag-management')" :active="request()->routeIs('admin.product-tag-management') ||
                        request()->routeIs('admin.product-tag-management.create') ||
                        request()->routeIs('admin.product-tag-management.view-tag-details')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-tags" viewBox="0 0 16 16">
                            <path
                                d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
                            <path
                                d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
                        </svg>
                        <span class="ps-5">
                            {{ __('Tags') }}
                        </span>
                    </x-nav-link>
                </div>
            </div>
            <!-- Voucher Promo -->
            <div>
                <x-nav-link :href="route('admin.voucher-management')" :active="request()->routeIs('admin.voucher-management') ||
                    request()->routeIs('admin.voucher-management.create') ||
                    request()->routeIs('admin.voucher-management.view-voucher-details')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-ticket-perforated" viewBox="0 0 16 16">
                        <path
                            d="M4 4.85v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9z" />
                        <path
                            d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3zM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9z" />
                    </svg>
                    <span class="ps-5">
                        {{ __('Voucher') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- Transaction Management -->
            <div>
                <x-nav-link :href="route('admin.transaction-management')" :active="request()->routeIs('admin.transaction-management') ||
                    request()->routeIs('admin.transaction-management.view-transaction-details')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-cash-stack" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                        <path
                            d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="ps-5">
                        {{ __('Transaction') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- Brand Partner -->
            <div>
                <x-nav-link :href="route('admin.brand-management')" :active="request()->routeIs('admin.brand-management') ||
                    request()->routeIs('admin.brand-management.create') ||
                    request()->routeIs('admin.brand-management.view-brand-details')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-chat-square-heart" viewBox="0 0 16 16">
                        <path
                            d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M8 3.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132" />
                    </svg>
                    <span class="ps-5">
                        {{ __('Brand Partner') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- Banner -->
            <div>
                <x-nav-link :href="route('admin.banner-management')" :active="request()->routeIs('admin.banner-management') ||
                    request()->routeIs('admin.banner-management.create') ||
                    request()->routeIs('admin.banner-management.view-banner-details')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-images" viewBox="0 0 16 16">
                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                        <path
                            d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z" />
                    </svg>
                    <span class="ps-5">
                        {{ __('Banners') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- FAQ -->
            <div>
                <x-nav-link :href="route('admin.faq-management')" :active="request()->routeIs('admin.faq-management') ||
                    request()->routeIs('admin.faq-management.create') ||
                    request()->routeIs('admin.faq-management.view-faq-details')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-chat" viewBox="0 0 16 16">
                        <path
                            d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105" />
                    </svg>
                    <span class="ps-5">
                        {{ __('FAQ Management') }}
                    </span>
                </x-nav-link>
            </div>
            <!-- Profile -->
            <div class="mt-12 pt-6 border-t border-gray-400">
                <div x-data="profile">
                    <button x-on:click="toggle"
                        class="w-full flex flex-row justify-between items-center px-5 py-2 text-xl font-medium text-black rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                        <div class="flex flex-row items-center gap-5">
                            <span class="">
                                @if (auth()->user()->hasMedia('user/profile_picture'))
                                    <img src="{{ auth()->user()->getFirstMediaUrl('user/profile_picture') }}"
                                        class="h-9 w-9 rounded-full object-cover" alt="Profile Picture" />
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                @endif
                            </span>
                            <span> {{ __(auth()->user()->name) }} </span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-chevron-down transition-transform duration-200 transform"
                            :class="rotateClasses">
                            <path fill-rule="evenodd"
                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 space-y-2 pl-4 w-full">
                        <x-nav-link class="w-full" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path
                                    d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z" />
                            </svg>
                            <span class="ps-5">
                                {{ __('Edit Profile') }}
                            </span>
                        </x-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="px-4 w-full flex flex-row items-center py-2 text-xl font-medium text-black rounded-md hover:bg-red-600 hover:text-white focus:outline-none focus:bg-red-600"
                                id="logout-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                                    <path fill-rule="evenodd"
                                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                                </svg>
                                <span class="ps-5">
                                    {{ __('Logout') }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Notifications -->
            <div>
                <x-nav-link class="flex flex-row items-center" href="{{ route('admin.notification') }}">
                    <div class="flex flex-row justify-between w-full">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                <path
                                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                            </svg>
                            <span class="ps-5">
                                {{ __('Notification') }}
                            </span>
                        </div>
                        <div class="flex flex-row items-center ">
                            <p class="bg-red-500 text-white px-2 py-1 rounded-[50%] ">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </p>
                        </div>
                    </div>
                </x-nav-link>
            </div>
        </nav>
    </div>
</aside>

<script>
    document.addEventListener('alpine:init', () => {


        Alpine.data('userManagement', () => ({
            open: {{ "request()->routeIs('admin.user-management')" ? 'true' : 'false' }},
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('productManagement', () => ({
            open: {{ request()->routeIs('admin.product-management') ||
            request()->routeIs('admin.product-category-management') ||
            request()->routeIs('admin.product-tag-management') ||
            request()->routeIs('admin.product-management.create') ||
            request()->routeIs('admin.product-tag-management.create') ||
            request()->routeIs('admin.product-category-management.create')
                ? 'true'
                : 'false' }},
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));

        Alpine.data('profile', () => ({
            open: {{ request()->routeIs('profile.edit') ? 'true' : 'false' }},
            get rotateClasses() {
                return {
                    'rotate-180': this.open,
                    'rotate-0': !this.open,
                };
            },
            toggle() {
                this.open = !this.open;
            }
        }));
    });
    document.getElementById('logout-button').addEventListener('click', function(event) {
        event.preventDefault();
        this.closest('form').submit();
    });
</script>

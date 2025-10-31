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
                <span class="mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" fill="currentColor"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                    </svg>
                </span>
                <li class="text-gray-500">
                    <a href="{{ route('admin.user-management.view-user-details', $user->id) }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        User Details
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('User Profile') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('View and edit profile for ') . $user->name . __(' here') }}
    </x-slot>

    <div class="py-12" id="user-profile">
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
        <div class="flex flex-row gap-10 ">
            <!-- Personal Information -->
            <div class="w-[70%]">
                <section class="gap-5 md:gap-10 border border-gray-400 rounded-xl p-5">
                    <h2 class="text-xl font-semibold pb-5">Personal Information</h2>
                    <div class="profile-details">
                        <p><strong>Username:</strong> {{ $user->username }}</p>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> {{ $user->role }}</p>
                        @if ($user->userAddress)
                            <p><strong>Address:</strong> {{ $user->userAddress->address }}</p>
                            <p><strong>City:</strong>
                                {{ $user->userAddress->city }}</p>
                            <p><strong>Subdistrict:</strong>
                                {{ $user->userAddress->subdistrict }}</p>
                            <p><strong>Province:</strong>
                                {{ $user->userAddress->province }}</p>
                            <p><strong>ZIP:</strong> {{ $user->userAddress->zip }}
                            </p>
                        @else
                            <p class="text-red-500"><strong>Address:</strong> Not Set</p>
                            <p class="text-red-500"><strong>City:</strong> Not Set</p>
                            <p class="text-red-500"><strong>Province:</strong> Not Set</p>
                            <p class="text-red-500"><strong>ZIP:</strong> Not Set</p>
                        @endif
                        <p><strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}</p>

                        <div class="pt-5 flex flex-row gap-5">
                            @if ($user->role === 'admin')
                                <form method="POST"
                                    onsubmit="return confirm('Are you sure you want to promote this admin?');"
                                    action="{{ route('admin.admin-management.promote', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="flex flex-row gap-5 bg-green-500 items-center rounded-md p-3 hover:bg-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="#ffffff" class="bi bi-arrow-up-circle text-white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                                        </svg>
                                        <p class="text-white"> Promote Role </p>
                                    </button>
                                </form>
                            @elseif($user->role === 'superadmin')
                                <form method="POST"
                                    onsubmit="return confirm('Are you sure you want to demote this admin?');"
                                    action="{{ route('admin.admin-management.demote', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="flex flex-row gap-5 bg-red-500 items-center rounded-md p-3 hover:bg-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="#ffffff" class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                                        </svg>
                                        <p class="text-white"> Demote Role </p>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
            <!-- Transaction Metrics -->
            <div class="flex flex-col justify-between w-teal-500 w-[30rem]">
                <x-metric-box border-color="border-gray-400"
                    title="Number of Transactions">{{ $user->transaction->count() }}</x-metric-box>
                <x-metric-box border-color="border-gray-400"
                    title="Total Completed Transactions Value">{{ 'Rp' . number_format($user->transaction->whereIn('transaction_status', ['Completed'])->sum('total'), 0, ',', '.') }}</x-metric-box>
                <x-metric-box border-color="border-gray-400"
                    title="Last Transaction Date">{{ $user->transaction->count() > 0 ? $user->transaction->sortByDesc('created_at')->first()->created_at->format('d M Y') : 'N/A' }}</x-metric-box>
            </div>
        </div>
        <!-- Latest Transactions -->
        <section>
            <h2 class="text-xl font-semibold py-5">Latest Transactions</h2>
            <div class="flex flex-col gap-5">
                @if ($user->transaction->count() > 0)
                    <table class="divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaction ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($user->transaction->take(10) as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $transaction->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->transaction_status }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a class="flex flex-row items-center gap-2 cursor-pointer"
                                            href="{{ route('admin.transaction-management.view-transaction-details', $transaction->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                            </svg>
                                            See Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-red-500">No transactions found</p>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>

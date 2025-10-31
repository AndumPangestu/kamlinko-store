<div class="flex flex-col gap-4">
    <div class="grid grid-flow-col gap-4 p-2 bg-gray-100">
        <div>
            <input type="text" id="search_username" name="search_username" value="{{ request('search_username') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search username" />
        </div>
        <div>
            <input type="text" id="search_email" name="search_email" value="{{ request('search_email') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search email" />
        </div>
        <div>
            <input type="text" id="search_name" name="search_name" value="{{ request('search_name') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search name" />
        </div>
        <div>
            <input type="text" id="search_role" name="search_role" value="{{ request('search_role') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search role" />
        </div>
        <div>
            <input type="text" id="search_address" name="search_address" value="{{ request('search_address') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search address" />
        </div>
        <div>
            <input type="text" id="search_city" name="search_city" value="{{ request('search_city') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search city" />
        </div>
        <div>
            <input type="text" id="search_zip" name="search_zip" value="{{ request('search_zip') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search zip" />
        </div>

    </div>
    @if (count($data) == 0)
        <div class="text-gray-600 text-center">
            <p>No Admin found.</p>
            <a href="{{ route('admin.user-management.create', ['mode' => 'admin']) }}" class="text-blue-600 underline">
                Add new Admin
            </a>
        </div>
    @else
        <div class="overflow-x-auto border rounded-xl">
            <table class="min-w-full table-auto">
                <!-- Table Header -->
                <thead class="divide-y divide-gray-200">
                    <tr>

                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/4">
                            Username
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Name
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Role
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Address
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            City
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            &nbsp;Zip
                        </th>
                        <th class="px-4 pt-2 text-right text-lg w-1/4">
                            <div class="flex flex-row justify-end items-center gap-5">
                                <span class="font-semibold text-gray-700">
                                    Action
                                </span>
                                <a href="{{ route('admin.user-management.create', ['mode' => 'admin']) }}"
                                    class="border border-gray-400 rounded-md p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                </a>
                            </div>
                        </th>
                    </tr>
                </thead>
                <!-- Table Footer - Pagination -->
                <tfoot class="border-t">
                    <tr>
                        <td colspan="9"> {{ $data->appends(request()->query())->links() }}
                        </td>
                    </tr>
                </tfoot>
                <!-- Table Body -->
                <tbody>

                    @foreach ($data as $admin)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                           
                            <td class="flex flex-col px-4 py-3 text-sm text-gray-800 truncate">
                                <p class="text-gray-800">{{ \Illuminate\Support\Str::limit($admin->username, 20) }}
                                </p>
                                <p class="text-sm text-gray-400">
                                    {{ \Illuminate\Support\Str::limit($admin->email, 20) }}</p>
                            </td>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ \Illuminate\Support\Str::limit($admin->name, 20) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ $admin->role === 'admin' ? 'Admin' : 'Super Admin' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 truncate">
                                {{ $admin->userAddress ? \Illuminate\Support\Str::limit($admin->userAddress->address, 20) : 'Not Set' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 truncate">
                                {{ $admin->userAddress ? \Illuminate\Support\Str::limit($admin->userAddress->city, 20) : 'Not Set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $admin->userAddress ? $admin->userAddress->zip : 'Not Set' }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3 text-gray-600 items-center h-full w-full">
                                    <a href="{{ route('admin.user-management.view-user-details', $admin->id) }}"
                                        class="mx-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </a>
                                    <span class="mx-2 cursor-pointer">
                                        @if (auth()->user()->id != $admin->id)
                                        <form class="flex items-center" method="POST"
                                            action="{{ route('admin.admin-management.destroy', ['mode' => $mode, 'id' => $admin->id]) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="29" height="20"
                                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                    <path
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

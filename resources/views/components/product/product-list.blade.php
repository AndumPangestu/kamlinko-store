<div class="flex flex-col gap-4">
    <div class="grid grid-flow-col gap-4 p-2 bg-gray-100">
        <div>
            <input type="text" id="search_sku" name="search_sku" value="{{ request('search_sku') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search SKU" />
        </div>
        <div>
            <input type="text" id="search_name" name="search_name" value="{{ request('search_name') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search Product Name" />
        </div>
        <div>
            <input type="text" id="search_category" name="search_category" value="{{ request('search_category') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search Category" />
        </div>
        <div>
            <input type="text" id="search_tag" name="search_tag" value="{{ request('search_tag') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search Tag" />
        </div>
        <div>
            <input type="text" id="search_brand" name="search_brand" value="{{ request('search_brand') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Search Brand" />
        </div>
        <div>
            <input type="text" id="search_price" name="search_price" value="{{ request('search_price') }}"
                class="w-full border border-gray-300 rounded-md p-1" placeholder="Price higher than" />
        </div>
        <div>
            <input type="text" id="search_highlight" name="search_highlight"
                value="{{ request('search_highlight') }}" class="w-full border border-gray-300 rounded-md p-1"
                placeholder="Search Highlight" />
        </div>
    </div>
    @if (count($data) == 0)
        <div class="text-gray-600 text-center">
            <p>No Product found.</p>
            <a href="{{ route('admin.product-management.create') }}" class="text-blue-600 underline">
                Add new Product
            </a>
        </div>
    @else
        <div class="overflow-x-auto border rounded-xl">
            <table class="min-w-full table-auto">
                <!-- Table Header -->
                <thead class="divide-y divide-gray-200">
                    <tr>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            SKU
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Name
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/6">
                            Category
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/8">
                            Tag
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/8">
                            &nbsp;Brand
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/8">
                            &nbsp;Price
                        </th>
                        <th class="px-4 pt-2 text-left text-lg font-semibold text-gray-700 w-1/8">
                            Views
                        </th>
                        <th class="px-4 pt-2 text-center text-lg font-semibold text-gray-700 w-1/12">
                            Stock
                        </th>
                        <th class="px-4 pt-2 text-center text-lg font-semibold text-gray-700 w-1/8">
                            Highlight
                        </th>
                        <th class="px-4 pt-2 text-center text-lg font-semibold text-gray-700 w-1/4">
                            Created by
                        </th>
                        <th class="px-4 pt-2 text-right text-lg w-1/6">
                            <div class="flex flex-row justify-end items-center gap-5">
                                <span class="font-semibold text-gray-700">
                                    Action
                                </span>
                                <a href="{{ route('admin.product-management.create') }}"
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
                        <td colspan="12"> {{ $data->appends(request()->query())->links() }}
                        </td>
                    </tr>
                </tfoot>
                <!-- Table Body -->
                <tbody>

                    @foreach ($data as $product)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            @if ($product->productType->count() > 0)
                                <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                    {{ \Illuminate\Support\Str::limit($product->productType->first()->sku, 20) }}
                                </td>
                            @else
                                <td class="px-4 py-3 text-sm text-red-500 truncate">
                                    No Product Type Yet!
                                </td>
                            @endif
                            <td class="px-4 py-3 text-sm text-gray-800 truncate">
                                {{ \Illuminate\Support\Str::limit($product->name, 20) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 truncate">
                                {{ \Illuminate\Support\Str::limit($product->category->name, 20) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400 truncate">
                                {{ $product->tag ? \Illuminate\Support\Str::limit($product->tag->name, 20) : 'Not Set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $product->brand ? \Illuminate\Support\Str::limit($product->brand->name, 20) : 'Not Set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                @if ($product->productType->count() == 1)
                                    Rp{{ number_format($product->productType->first()->price, 0, ',', '.') }}
                                @elseif ($product->productType->count() > 1)
                                    @if ($product->productType->min('price') == $product->productType->max('price'))
                                        Rp{{ number_format($product->productType->min('price'), 0, ',', '.') }}
                                    @else
                                        Rp{{ number_format($product->productType->min('price'), 0, ',', '.') }} -
                                        Rp{{ number_format($product->productType->max('price'), 0, ',', '.') }}
                                    @endif
                                @else
                                    Not Set
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 truncate">
                                {{ $product->views }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm {{ $product->productType->sum('stock') == 0 ? 'text-red-500' : 'text-gray-800' }} truncate">
                                {{ $product->productType->sum('stock') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-400">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->put_on_highlight == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->put_on_highlight == 1 ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 truncate">
                                {{ isset($product->created_by) ? $product->admin_create->name : 'Unknown' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3 text-gray-600 items-center h-full w-full">
                                    <a href="{{ route('admin.product-management.view-product-details', $product->id) }}"
                                        class="mx-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                    <span class="mx-2 cursor-pointer">
                                        <form class="flex items-center" method="POST"
                                            action="{{ route('admin.product-management.destroy', $product->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
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

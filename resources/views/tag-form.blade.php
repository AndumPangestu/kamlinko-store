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
                    <a href="{{ route('admin.product-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Product Management
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
                    <a href="{{ route('admin.product-tag-management') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        Tag
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
                    <a href="{{ isset($tag) ? route('admin.product-tag-management.view-tag-form', $tag->id) : route('admin.product-tag-management.create') }}"
                        class="text-[#1737A4] hover:text-blue-500 font-semibold text-lg">
                        {{ isset($tag) ? 'Edit Tag' : 'Add Tag' }}
                    </a>
                </li>
            </ol>
        </nav>
    </x-slot>
    <x-slot name="title">
        {{ __('Add Tag') }}
    </x-slot>
    <x-slot name="headerDesc">
        {{ __('Add a new tag here') }}
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
        <!-- Product details in general -->
        <section class="rounded-lg border border-gray-300 p-5">
            <h2 class="text-2xl font-semibold">Tag Details</h2>
            <form class="pt-5 flex flex-col gap-2" method="POST"
                action="{{ isset($tag) ? route('admin.product-tag-management.update', $tag->id) : route('admin.product-tag-management.store') }}">
                @csrf
                @if (isset($tag))
                    @method('PUT')
                @endif
                <span>
                    <label for="name" class="text-lg font-semibold">Tag Name</label>
                    <input type="text" name="name" id="tag-name"
                        value="{{ old('name', isset($tag) ? $tag->name : '') }}"
                        class="w-full border border-gray-300 rounded-lg p-2" placeholder="Enter tag name">
                </span>

                <span>
                    <label for="description" class="text-lg font-semibold">Description</label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Enter tag description" rows="5">{{ old('description', isset($tag) ? $tag->description : '') }}</textarea>
                </span>

                @if (isset($tag))
                    <div class="flex flex-row gap-10">
                        <span class="text-sm text-gray-500">
                            <p>Created at:</p>
                            <p>{{ $tag->created_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($tag->created_by) ? route('admin.user-management.view-user-details', $tag->admin_create->id) : '#' }}">
                                    {{ isset($tag->created_by) ? $tag->admin_create->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                        <span class="text-sm text-gray-500">
                            <p>Updated at:</p>
                            <p>{{ $tag->updated_at }}</p>
                            <span>
                                by
                                <a class="hover:text-blue-500"
                                    href="{{ isset($tag->updated_by) ? route('admin.user-management.view-user-details', $tag->admin_update->id) : '#' }}">
                                    {{ isset($tag->updated_by) ? $tag->admin_update->name : 'Unknown/Deleted Admin' }}
                                </a>
                            </span>
                        </span>
                    </div>
                @endif

                <span class="pt-5">
                    <button type="submit"
                        class="bg-[#1737A4] text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save Tag</button>
                </span>
            </form>
        </section>
    </div>
</x-app-layout>

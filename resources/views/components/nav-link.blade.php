@props(['active' => null])

@php
    $classes =
        $active ?? false
            ? 'inline-flex w-full items-center px-5 py-4 rounded-lg border-l-[6px] border-[#1737A4] dark:border-indigo-600 text-normal font-semibold leading-5 text-[#1737A4] dark:text-gray-100 bg-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex w-full items-center px-5 py-4 rounded-lg text-normal font-medium leading-5 text-gray-700 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

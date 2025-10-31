@props(['placeholder', 'value'])

<div class="relative">
    <input type="text" id="search" class="pl-10 px-2 py-2 border-gray-300 rounded-md"
        placeholder="{{ isset($placeholder) ? $placeholder : 'Search' }}" value="{{ isset($value) ? $value : '' }}" />
    <div class="absolute left-0 pl-3 
            flex items-center 
            pointer-events-none" id="searchIcon">
        <svg class="w-5 h-5" fill="none" stroke="gray" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
</div>

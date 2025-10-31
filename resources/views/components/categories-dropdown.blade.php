@foreach ($categories->sortBy('name') as $category)
    <div class="option" data-value="{{ $category->id }}"
        {{ old('category_id', $selectedCategoryId) == $category->id ? 'data-selected="true"' : '' }}
        style="display: block;">
        {{ str_repeat('--', $level) }} {{ $category->name }}
    </div>
    @if ($category->children->isNotEmpty())
        @include('components.categories-dropdown', [
            'categories' => $category->children,
            'level' => $level + 1,
            'selectedCategoryId' => old('category_id', $selectedCategoryId),
        ])
    @endif
@endforeach

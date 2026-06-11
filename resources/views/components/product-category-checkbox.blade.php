@props(['category', 'groupedCategories', 'depth' => 0])

<div class="{{ $depth > 0 ? 'pl-4 border-l border-gray-100 ml-2' : '' }} space-y-1">
    <label class="flex items-center {{ $depth === 0 ? 'p-2' : 'p-1.5' }} rounded hover:bg-gray-50 w-full cursor-pointer">
        <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-btn-primary-ring">
        <span class="ml-2 {{ $depth === 0 ? 'text-sm font-semibold text-black' : 'text-xs text-gray-700' }}">
            {{ $category->name }}
        </span>
    </label>

    @if($children = $groupedCategories->get($category->id))
        <div class="space-y-1">
            @foreach($children as $child)
                <x-product-category-checkbox :category="$child" :grouped-categories="$groupedCategories" :depth="$depth + 1" />
            @endforeach
        </div>
    @endif
</div>

@props(['children', 'depth' => 1])

@foreach($children as $child)
    @php
        $indentPx  = $depth * 24; // 24px extra indent per level
        $isParent  = $child->is_parent || $child->children->isNotEmpty();
        $textColor = $isParent
            ? 'text-gray-900 font-semibold'
            : match(true) {
                $depth === 1 => 'text-purple-600',
                $depth === 2 => 'text-indigo-500',
                default      => 'text-teal-600',
            };
        $rowBg = $isParent
            ? 'bg-blue-50/30'
            : match(true) {
                $depth === 1 => 'bg-gray-50/20',
                $depth === 2 => 'bg-purple-50/20',
                default      => 'bg-indigo-50/20',
            };
    @endphp

    <tr class="hover:bg-gray-50 transition {{ $rowBg }}">
        {{-- No --}}
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>

        {{-- Image --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($child->image_path)
                <span class="inline-flex rounded-xl bg-gray-100 p-1">
                    <img src="{{ Storage::url($child->image_path) }}" class="h-10 w-10 object-cover rounded-lg" alt="Category image">
                </span>
            @else
                <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs text-gray-400 font-medium">
                    No Image
                </span>
            @endif
        </td>

        {{-- Name --}}
        <td class="py-4 whitespace-nowrap" style="padding-left: {{ $indentPx }}px">
            <div class="flex items-center text-sm {{ $textColor }}">
                <span class="text-gray-400 mr-2">└───</span>
                {{ $child->name }}
                @if($isParent)
                    <span class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">parent</span>
                @endif
            </div>
            <div class="text-xs text-gray-500 pl-8">
                #{{ $child->id }}
            </div>
        </td>

        {{-- Is Parent --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($child->is_parent)
                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">
                    Yes
                </span>
            @else
                <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-0.5 text-xs text-gray-400 font-medium">
                    No
                </span>
            @endif
        </td>

        {{-- Actions --}}
        <td class="px-6 py-4 whitespace-nowrap text-right">
            <div class="flex items-center justify-end gap-3">
                @can('product-categories.create')
                    <button wire:click="createSubCategory({{ $child->id }})" class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors" title="Add Sub Category">
                        + Sub Category
                    </button>
                @endcan
                <div class="flex justify-end gap-2">
                    @can('product-categories.edit')
                        <button wire:click="edit({{ $child->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                    @endcan
                    @can('product-categories.delete')
                        @if(!in_array($child->slug, ['discontinued', 'most-needed']))
                            <button wire:confirm="Are you sure you want to delete this category?" wire:click="delete({{ $child->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        @endif
                    @endcan
                </div>
            </div>
        </td>
    </tr>

    {{-- Recurse into this child's own children --}}
    @if($child->children->isNotEmpty())
        <x-product-category-children :children="$child->children" :depth="$depth + 1" />
    @endif
@endforeach

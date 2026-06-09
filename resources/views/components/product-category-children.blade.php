@props(['children', 'depth' => 1])

@foreach($children as $child)
    @php
        $indentPx  = 24 + ($depth * 28); // grows with depth
        $isParent  = $child->is_parent || $child->children->isNotEmpty();
        $textColor = $isParent ? 'text-gray-800 font-semibold' : 'text-gray-600';
        $rowBg     = $isParent ? 'hover:bg-indigo-50/30' : 'hover:bg-gray-50/60';
        $avatarBg  = $isParent ? 'bg-indigo-50 text-indigo-600 ring-indigo-100' : 'bg-gray-100 text-gray-500 ring-gray-200';
        $borderAccent = $isParent ? 'border-l-2 border-l-indigo-200' : 'border-l-2 border-l-transparent';
    @endphp

    <tr class="{{ $rowBg }} {{ $borderAccent }} transition-colors group">
        {{-- Category --}}
        <td class="py-3 pr-6" style="padding-left: {{ $indentPx }}px">
            <div class="flex items-center gap-3">
                {{-- Tree connector --}}
                <span class="text-gray-300 text-xs shrink-0">└</span>

                {{-- Avatar --}}
                @if($child->image_path)
                    <img src="{{ Storage::url($child->image_path) }}"
                        class="h-8 w-8 rounded-lg object-cover ring-1 ring-gray-200 shrink-0"
                        alt="{{ $child->name }}">
                @else
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg {{ $avatarBg }} font-semibold text-xs shrink-0 ring-1 select-none">
                        {{ strtoupper(mb_substr($child->name, 0, 1)) }}
                    </span>
                @endif

                {{-- Name --}}
                <span class="text-sm {{ $textColor }}">{{ $child->name }}</span>
            </div>
        </td>

        {{-- Actions --}}
        <td class="px-6 py-3 text-right whitespace-nowrap">
            <div class="inline-flex items-center gap-1.5">
                @can('product-categories.create')
                    <button wire:click="createSubCategory({{ $child->id }})"
                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors"
                        title="Add Sub Category">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Sub
                    </button>
                @endcan
                @can('product-categories.edit')
                    <button wire:click="edit({{ $child->id }})"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                @endcan
                @can('product-categories.delete')
                    @if(!in_array($child->slug, ['discontinued', 'most-needed']))
                        <button wire:confirm="Are you sure you want to delete this category?" wire:click="delete({{ $child->id }})"
                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                @endcan
            </div>
        </td>
    </tr>

    {{-- Recurse --}}
    @if($child->children->isNotEmpty())
        <x-product-category-children :children="$child->children" :depth="$depth + 1" />
    @endif
@endforeach

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Brands</h1>
        <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add Brand
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Search -->
            <div class="col-span-1">
                <label for="search"
                    class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative rounded-md shadow-sm">
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                        class="w-full rounded-lg border px-3 py-2 bg-white text-black"
                        style="border: 1px solid #D2D2D2;" placeholder="Name or Slug...">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-span-1">
                <label for="status"
                    class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status" id="status"
                    class="w-full rounded-lg border px-3 py-2 bg-white text-black"
                    style="border: 1px solid #D2D2D2;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Sort -->
            <div class="col-span-1">
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort
                    By</label>
                <div class="flex gap-2">
                    <select wire:model.live="sortField" id="sortField"
                        class="w-full rounded-lg border px-3 py-2 bg-white text-black"
                        style="border: 1px solid #D2D2D2;">
                        <option value="sort_order">Order</option>
                        <option value="name">Name</option>
                        <option value="created_at">Date Created</option>
                    </select>
                    <select wire:model.live="sortDirection" id="sortDirection"
                        class="w-full rounded-lg border px-3 py-2 bg-white text-black"
                        style="border: 1px solid #D2D2D2;">
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
                </div>
            </div>

            <!-- Reset -->
            <div class="col-span-1">
                <button wire:click="resetFilters"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-[#0079C2] text-[#0079C2] shadow-sm text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition hover:cursor-pointer">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($brands as $brand)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($brand->image)
                                    <img src="{{ Storage::url($brand->image) }}" alt="{{ $brand->name }}"
                                        class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-500">No Img</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-black">{{ $brand->name }}</div>
                                <div class="text-sm text-gray-500">{{ $brand->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $brand->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<a href="{{ route('admin.brands.edit', $brand->id) }}" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                <button wire:confirm="Are you sure?" wire:click="delete({{ $brand->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
</div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No brands found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $brands->links() }}
        </div>
    </div>
</div>

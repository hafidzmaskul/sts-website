<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100">Brands</h1>
        <a href="{{ route('admin.brands.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add Brand
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-4 border border-gray-200 dark:border-zinc-700">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Search -->
            <div class="col-span-1">
                <label for="search"
                    class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Search</label>
                <div class="relative rounded-md shadow-sm">
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-zinc-100"
                        style="border: 1px solid #D2D2D2;" placeholder="Name or Slug...">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-span-1">
                <label for="status"
                    class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Status</label>
                <select wire:model.live="status" id="status"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-zinc-100"
                    style="border: 1px solid #D2D2D2;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Sort -->
            <div class="col-span-1">
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Sort
                    By</label>
                <div class="flex gap-2">
                    <select wire:model.live="sortField" id="sortField"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-zinc-100"
                        style="border: 1px solid #D2D2D2;">
                        <option value="sort_order">Order</option>
                        <option value="name">Name</option>
                        <option value="created_at">Date Created</option>
                    </select>
                    <select wire:model.live="sortDirection" id="sortDirection"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-zinc-100"
                        style="border: 1px solid #D2D2D2;">
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
                </div>
            </div>

            <!-- Reset -->
            <div class="col-span-1">
                <button wire:click="resetFilters"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-[#0079C2] text-[#0079C2] shadow-sm text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 dark:hover:bg-zinc-600 transition hover:cursor-pointer">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-800 shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Order
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($brands as $brand)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($brand->image)
                                    <img src="{{ Storage::url($brand->image) }}" alt="{{ $brand->name }}"
                                        class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-200 dark:bg-zinc-700 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-500 dark:text-zinc-400">No Img</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-zinc-100">{{ $brand->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-zinc-400">{{ $brand->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                {{ $brand->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4">Edit</a>
                                <button wire:click="delete({{ $brand->id }})" wire:confirm="Are you sure?"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">
                                No brands found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-zinc-700">
            {{ $brands->links() }}
        </div>
    </div>
</div>
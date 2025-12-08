<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Product: {{ $title }}</h1>
        <a href="{{ route('admin.products.index') }}"
            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
            &larr; Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700">
        <form wire:submit.prevent="save">
            @include('livewire.admin.products.product-form')

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.products.index') }}"
                    class="mr-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700 dark:hover:bg-zinc-700">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold" style="color:#AEAEAE;">Edit Product: {{ $title }}</h1>
        <a href="{{ route('admin.products.index') }}"
            class="text-[#0079C2] border border-[#0079C2] hover:bg-[#0079C2] hover:text-white px-4 py-2 rounded-lg transition">
            &larr; Back to List
        </a>
    </div>

    <div class="rounded-xl shadow p-6 border border-zinc-200">
        <form wire:submit.prevent="save">
            @include('livewire.admin.products.product-form')

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.products.index') }}"
                    class="mr-3 inline-flex justify-center rounded-lg border border-[#0079C2] px-4 py-2 text-sm font-semibold text-[#0079C2] transition hover:bg-[#0079C2] hover:text-white">
                    Cancel
                </a>
                <button type="submit"
                    class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-black">Edit Product</h1>
            <p class="text-sm text-black mt-1">Updating: <span class="font-medium text-black">{{ $title }}</span>
            </p>
        </div>

    </div>

    <form wire:submit.prevent="save" id="product-form">
        @include('livewire.admin.products.product-form')
    </form>
</div>

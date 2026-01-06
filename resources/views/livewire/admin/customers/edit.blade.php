<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Edit Customer</h1>
    </div>

    <div class="p-6 rounded-2xl shadow">
        <form wire:submit.prevent="save" class="space-y-5">
            <h2 class="text-lg font-semibold border-b border-[#AEAEAE] pb-2 mb-4 text-black">User Account</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Name</label>
                    <input type="text" wire:model="name"
                        class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                        placeholder="Name">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                        placeholder="Email">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Pricing Formula -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-black">Pricing Formula</label>
                <div class="text-xs text-gray-500 mb-2">Select a pricing formula to apply automatically.</div>
                <select wire:model="pricing_formula_id"
                    class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black">
                    <option value="">None</option>
                    @foreach($pricingFormulas as $formula)
                        <option value="{{ $formula->id }}">{{ $formula->label }} ({{ $formula->type->label() }}
                            {{ $formula->value }})</option>
                    @endforeach
                </select>
                @error('pricing_formula_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    </div>

    <h2 class="text-lg font-semibold border-b border-[#AEAEAE] pb-2 mb-4 mt-8 text-black">Profile
        Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Status -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1 text-black">Status</label>
            <select wire:model="status"
                class="w-full md:w-1/2 rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-sm font-medium mb-1 text-black">Phone</label>
            <input type="text" wire:model="phone"
                class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                placeholder="Phone">
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Postal Code -->
        <div>
            <label class="block text-sm font-medium mb-1 text-black">Postal Code</label>
            <input type="text" wire:model="postal_code"
                class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                placeholder="Postal Code">
            @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Address -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1 text-black">Address</label>
            <textarea wire:model="address" rows="2"
                class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                placeholder="Address"></textarea>
            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- City -->
        <div>
            <label class="block text-sm font-medium mb-1 text-black">City</label>
            <input type="text" wire:model="city"
                class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                placeholder="City">
            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Country -->
        <div>
            <label class="block text-sm font-medium mb-1 text-black">Country</label>
            <input type="text" wire:model="country"
                class="w-full rounded-lg border border-[#AEAEAE] px-3 py-2 text-black placeholder-black"
                placeholder="Country">
            @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('admin.customers.index') }}"
            class="px-4 py-2 border border-[#0079C2] rounded-lg text-[#0079C2] bg-white hover:bg-[#f0f8ff] transition w-full md:w-auto text-center">Cancel</a>
        <button type="submit"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save
            Changes</button>
    </div>
    </form>
</div>
</div>

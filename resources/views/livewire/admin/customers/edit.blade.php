<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Customer</h1>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
        <form wire:submit.prevent="save" class="space-y-5">
            <h2 class="text-lg font-semibold border-b pb-2 mb-4 dark:text-white dark:border-zinc-700">User Account</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Name</label>
                    <input type="text" wire:model="name"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <h2 class="text-lg font-semibold border-b pb-2 mb-4 mt-8 dark:text-white dark:border-zinc-700">Profile
                Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Status</label>
                    <select wire:model="status"
                        class="w-full md:w-1/2 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Phone</label>
                    <input type="text" wire:model="phone"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Postal Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Postal Code</label>
                    <input type="text" wire:model="postal_code"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Address</label>
                    <textarea wire:model="address" rows="2"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"></textarea>
                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">City</label>
                    <input type="text" wire:model="city"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Country</label>
                    <input type="text" wire:model="country"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.customers.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">Save
                    Changes</button>
            </div>
        </form>
    </div>
</div>
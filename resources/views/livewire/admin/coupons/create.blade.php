<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-black">Create Coupon</h1>
            <p class="text-sm text-black mt-1">Create a new discount coupon</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}"
            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            Cancel
        </a>
    </div>

    <form wire:submit="save" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 space-y-6">
            {{-- Type & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label for="type" class="block text-sm font-medium text-black">Coupon Type</label>
                    <div class="flex items-center space-x-4 mt-2">
                        <div class="flex items-center">
                            <input id="type_redeem" name="type" type="radio" value="redeem" wire:model.live="type"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="type_redeem" class="ml-2 block text-sm font-medium text-black">
                                Redeem (Use Code)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="type_claim" name="type" type="radio" value="claim" wire:model.live="type"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="type_claim" class="ml-2 block text-sm font-medium text-black">
                                Claim (Auto-Apply)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="status" class="block text-sm font-medium text-black">Status</label>
                    <select id="status" wire:model="status"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black">
                        <option value="published">Published</option>
                        <option value="unpublished">Unpublished</option>
                    </select>
                    @error('status') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Basic Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-medium text-black">Coupon Name</label>
                    <input type="text" id="name" wire:model="name"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">
                    @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                @if($type === 'redeem')
                    <div class="space-y-1">
                        <label for="code" class="block text-sm font-medium text-black">Coupon Code</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" id="code" wire:model="code"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-lg border border-[#D2D2D2] focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">
                            <button type="button" wire:click="generateCode"
                                class="inline-flex items-center px-4 py-2 border border-l-0 border-[#D2D2D2] rounded-r-lg bg-gray-50 text-sm font-medium text-black hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                Generate
                            </button>
                        </div>
                        @error('code') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>

            {{-- Discount --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label for="discount_type" class="block text-sm font-medium text-black">Discount Type</label>
                    <select id="discount_type" wire:model.live="discount_type"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black">
                        <option value="fixed">Fixed Amount (£)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                    @error('discount_type') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="quota" class="block text-sm font-medium text-black">Quota (Total Usage Limit)</label>
                    <input type="number" id="quota" wire:model="quota" placeholder="Leave blank for unlimited" min="1"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-gray-400">
                    <p class="text-xs text-gray-500">Number of times this coupon can be used in total.</p>
                    @error('quota') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="discount_value" class="block text-sm font-medium text-black">Discount Value</label>
                    <div class="relative rounded-md shadow-sm">
                        @if($discount_type === 'fixed')
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-black sm:text-sm">£</span>
                            </div>
                        @endif
                        <input type="number" step="0.01" id="discount_value" wire:model="discount_value"
                            class="block w-full {{ $discount_type === 'fixed' ? 'pl-7' : 'pl-3' }} px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">
                        @if($discount_type === 'percentage')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-black sm:text-sm">%</span>
                            </div>
                        @endif
                    </div>
                    @error('discount_value') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label for="start_date" class="block text-sm font-medium text-black">Start Date (Optional)</label>
                    <input type="date" id="start_date" wire:model="start_date"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">
                    @error('start_date') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="end_date" class="block text-sm font-medium text-black">End Date (Optional)</label>
                    <input type="date" id="end_date" wire:model="end_date"
                        class="block w-full px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">
                    @error('end_date') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Restriction --}}
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-black">Usage Restrictions</h3>

                <div class="space-y-2">
                    <div class="flex items-center">
                        <input id="restrict_none" name="restriction_type" type="radio" value="none"
                            wire:model.live="restriction_type"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="restrict_none" class="ml-3 block text-sm font-medium text-black">
                            No Restrictions (Anyone can use)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="restrict_role" name="restriction_type" type="radio" value="role"
                            wire:model.live="restriction_type"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="restrict_role" class="ml-3 block text-sm font-medium text-black">
                            Restrict by User Level (Role)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="restrict_user" name="restriction_type" type="radio" value="specific_user"
                            wire:model.live="restriction_type"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="restrict_user" class="ml-3 block text-sm font-medium text-black">
                            Restrict to Specific Users
                        </label>
                    </div>
                </div>

                @if($restriction_type === 'role')
                    <div class="mt-4 pl-7">
                        <label for="role_level" class="block text-sm font-medium text-black">Select User Level</label>
                        <select id="role_level" wire:model="role_level"
                            class="mt-1 block w-full max-w-md pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md text-black border-[#D2D2D2] border">
                            <option value="">Select Level...</option>
                            <option value="trade account">Trade Account</option>
                            <option value="credit facilities account">Credit Facilities Account</option>
                            <option value="guest">Guest</option>
                        </select>
                        @error('role_level') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if($restriction_type === 'specific_user')
                    <div class="mt-4 pl-7 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-black mb-1">Search & Add Users</label>
                            <input type="text" wire:model.live.debounce.300ms="userSearch"
                                placeholder="Type name or email to search..."
                                class="block w-full max-w-md px-3 py-2 border border-[#D2D2D2] rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black placeholder-black">

                            @if(!empty($userSearch) && count($users) > 0)
                                <ul
                                    class="mt-1 w-full max-w-md bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto z-10">
                                    @foreach($users as $user)
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center"
                                            wire:click="$set('specific_users', {{ json_encode(array_unique(array_merge($specific_users, [$user->id]))) }}); $set('userSearch', '');">
                                            <div>
                                                <div class="text-sm font-medium text-black">{{ $user->name }}</div>
                                                <div class="text-xs text-black">{{ $user->email }}</div>
                                            </div>
                                            @if(in_array($user->id, $specific_users))
                                                <span class="text-xs text-green-600 font-bold">Selected</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif(!empty($userSearch))
                                <div class="mt-1 text-sm text-black">No users found.</div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Selected Users
                                ({{ count($specific_users) }})</label>
                            <div class="space-y-2">
                                @foreach(\App\Models\User::whereIn('id', $specific_users)->get() as $selectedUser)
                                    <div
                                        class="flex items-center justify-between max-w-md p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div>
                                            <div class="text-sm font-medium text-black">{{ $selectedUser->name }}</div>
                                            <div class="text-xs text-black">{{ $selectedUser->email }}</div>
                                        </div>
                                        <button type="button"
                                            wire:click="$set('specific_users', {{ json_encode(array_diff($specific_users, [$selectedUser->id])) }})"
                                            class="text-black hover:text-red-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                                @if(count($specific_users) === 0)
                                    <p class="text-sm text-black italic">No users selected yet.</p>
                                @endif
                                @error('specific_users') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}"
                class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Create Coupon
            </button>
        </div>
    </form>
</div>
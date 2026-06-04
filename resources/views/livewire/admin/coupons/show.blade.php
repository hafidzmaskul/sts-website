<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-black">Coupon Details</h1>
            <p class="text-sm text-black mt-1">Detailed view of coupon {{ $coupon->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.coupons.index') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
                Back to List
            </a>
            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
                Edit Coupon
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Main Info --}}
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-black border-b border-gray-100 pb-2 mb-4">Identification</h3>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-black font-semibold">{{ $coupon->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Code</dt>
                            <dd class="mt-1 text-sm font-mono font-bold text-black">{{ $coupon->code ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-sm text-black capitalize">{{ $coupon->type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                @if($coupon->status === 'published')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Published
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        Unpublished
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-black border-b border-gray-100 pb-2 mb-4">Value & Usage</h3>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Discount</dt>
                            <dd class="mt-1 text-sm text-black font-semibold">
                                @if($coupon->discount_type === 'percentage')
                                    {{ $coupon->discount_value }}%
                                @else
                                    £{{ number_format($coupon->discount_value, 2) }}
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Discount Type</dt>
                            <dd class="mt-1 text-sm text-black capitalize">{{ $coupon->discount_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Quota (Limit)</dt>
                            <dd class="mt-1 text-sm text-black">
                                {{ $coupon->quota ? $coupon->quota . ' uses' : 'Unlimited' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sold (Used)</dt>
                            <dd class="mt-1 text-sm font-bold text-emerald-600">{{ $coupon->used_count }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Validity & Restrictions --}}
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-black border-b border-gray-100 pb-2 mb-4">Validity Period</h3>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm text-black">
                                {{ $coupon->start_date ? $coupon->start_date->format('M d, Y') : 'Immediately' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-sm text-black">
                                {{ $coupon->end_date ? $coupon->end_date->format('M d, Y') : 'Never Expires' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-black border-b border-gray-100 pb-2 mb-4">Restrictions</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Restriction Type</dt>
                            <dd class="mt-1 text-sm text-black">
                                @if($coupon->restriction_type === 'role')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Role-Based
                                    </span>
                                @elseif($coupon->restriction_type === 'specific_user')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Specific Users
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        No Restrictions
                                    </span>
                                @endif
                            </dd>
                        </div>

                        @if($coupon->restriction_type === 'role')
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Allowed Role</dt>
                                <dd class="mt-1 text-sm text-black font-mono bg-gray-50 p-1 rounded inline-block">
                                    {{ ucfirst($coupon->role_level) }}</dd>
                            </div>
                        @endif

                        @if($coupon->restriction_type === 'specific_user')
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Allowed Users
                                    ({{ $coupon->users->count() }})</dt>
                                <dd
                                    class="text-sm text-black bg-gray-50 rounded-lg border border-gray-200 p-3 max-h-40 overflow-y-auto">
                                    <ul class="space-y-1">
                                        @foreach($coupon->users as $user)
                                            <li class="flex justify-between">
                                                <span>{{ $user->name }}</span>
                                                <span class="text-gray-500 text-xs">{{ $user->email }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
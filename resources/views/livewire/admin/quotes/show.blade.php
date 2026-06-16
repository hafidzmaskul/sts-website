<div class="p-6 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.quotes.index') }}" wire:navigate
            class="flex items-center text-black hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-arrow-left">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold" style="color: #000;">Quote Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quote Details (Left/Main Panel - 2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border p-6 space-y-6">
                <h2 class="text-lg font-semibold text-black">Quote Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Name</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->first_name }} {{ $quote->last_name }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Company</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->company_name }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Email</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->email }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Phone</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->phone }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Country</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->country }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Postal Code</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->postal_code }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Date Submitted</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->created_at->format('M d, Y H:i:s') }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Source Page</label>
                        <div class="mt-1 text-lg text-black">{{ $quote->source_page ?? 'Direct API' }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-black uppercase">Marketing Opt-in</label>
                        <div class="mt-1 text-lg text-black">
                            @if($quote->marketing_opt_in)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black">
                                    No
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black uppercase">Project Details</label>
                    <div class="mt-2 text-base text-black bg-gray-50 p-4 rounded-lg whitespace-pre-wrap">{{ $quote->project_details }}</div>
                </div>
            </div>
        </div>

        <!-- Registration & Activity Info (Right Panel - 1/3 width) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Registration Card -->
            <div class="bg-white rounded-xl border p-6 space-y-4">
                <h3 class="text-lg font-semibold text-black border-b pb-2">User Registration</h3>
                @if($registeredUser)
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Registered Name</label>
                            <div class="mt-1 text-sm font-semibold text-black">{{ $registeredUser->name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Account Number</label>
                            <div class="mt-1 text-sm font-semibold text-black">{{ $registeredUser->customer?->account_number ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Associated Company</label>
                            <div class="mt-1 text-sm font-semibold text-black">
                                @if($registeredUser->customer?->company)
                                    {{ $registeredUser->customer->company->name }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            Not Registered
                        </span>
                        <p class="text-xs text-gray-500 mt-2">No registered user matches this quote's email address.</p>
                    </div>
                @endif
            </div>

            @if($registeredUser)
                <!-- Quote Builder Drafts Card -->
                <div class="bg-white rounded-xl border p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-black border-b pb-2">Quote Builder Drafts</h3>
                    @if(count($quoteBuilders) > 0)
                        <div class="space-y-4 max-h-60 overflow-y-auto">
                            @foreach($quoteBuilders as $builder)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="text-sm font-semibold text-black">{{ $builder->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Created: {{ $builder->created_at->format('M d, Y') }}</div>
                                    <ul class="list-disc pl-4 mt-2 text-xs text-gray-700 space-y-1">
                                        @foreach($builder->products as $product)
                                            <li>{{ $product->title }} (Qty: {{ $product->pivot->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">No Quote Builders found.</div>
                    @endif
                </div>

                <!-- Shopping Cart Card -->
                <div class="bg-white rounded-xl border p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-black border-b pb-2">Active Cart Items</h3>
                    @if(count($cartItems) > 0)
                        <ul class="list-disc pl-5 text-sm text-gray-700 space-y-2 max-h-48 overflow-y-auto">
                            @foreach($cartItems as $item)
                                @if($item->product)
                                    <li>
                                        <span class="font-medium text-black">{{ $item->product->title }}</span>
                                        <span class="text-xs text-gray-500">(Qty: {{ $item->quantity }})</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <div class="text-sm text-gray-500 italic">No items in cart.</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

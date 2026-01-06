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

    <div class="bg-white rounded-xl border p-6 space-y-6">
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
                <label class="block text-sm font-medium text-black uppercase">Marketing Opt-in</label>
                <div class="mt-1 text-lg text-black">
                    @if($quote->marketing_opt_in)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Yes
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black">
                            No
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-black uppercase">Project Details</label>
            <div class="mt-2 text-base text-black bg-gray-50 p-4 rounded-lg whitespace-pre-wrap">
                {{ $quote->project_details }}</div>
        </div>
    </div>
</div>

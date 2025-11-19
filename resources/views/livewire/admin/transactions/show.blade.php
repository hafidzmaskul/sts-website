<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Invoice {{ $transaction->invoice_code }}
            </h1>
            <a 
                href="{{ route('admin.transactions.index') }}" 
                wire:navigate
                class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
            >
                Back to List
            </a>
        </div>
    </header>

    <div class="mx-auto max-w-4xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="rounded-xl p-4 border flex items-center justify-between
            @if($transaction->status === 'paid') bg-green-50 border-green-200 dark:bg-green-900/30 dark:border-green-800
            @elseif($transaction->status === 'pending') bg-yellow-50 border-yellow-200 dark:bg-yellow-900/30 dark:border-yellow-800
            @else bg-red-50 border-red-200 dark:bg-red-900/30 dark:border-red-800 @endif">
            
            <div class="flex items-center gap-3">
                <span class="font-semibold uppercase tracking-wide
                    @if($transaction->status === 'paid') text-green-700 dark:text-green-400
                    @elseif($transaction->status === 'pending') text-yellow-700 dark:text-yellow-400
                    @else text-red-700 dark:text-red-400 @endif">
                    {{ $transaction->status }}
                </span>
                @if($transaction->square_payment_id)
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">| Payment ID: {{ $transaction->square_payment_id }}</span>
                @endif
            </div>
            <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <h3 class="text-lg font-semibold mb-4 dark:text-white border-b pb-2 dark:border-zinc-700">Customer Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <label class="block text-zinc-500 dark:text-zinc-400">Name</label>
                        <p class="font-medium dark:text-zinc-200">{{ $transaction->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-zinc-500 dark:text-zinc-400">Email</label>
                        <p class="font-medium dark:text-zinc-200">{{ $transaction->email }}</p>
                    </div>
                    <div>
                        <label class="block text-zinc-500 dark:text-zinc-400">Phone</label>
                        <p class="font-medium dark:text-zinc-200">{{ $transaction->mobile_phone }}</p>
                    </div>
                    <div>
                        <label class="block text-zinc-500 dark:text-zinc-400">Address</label>
                        <p class="font-medium dark:text-zinc-200">
                            {{ $transaction->town_city }}, {{ $transaction->postcode }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <h3 class="text-lg font-semibold mb-4 dark:text-white border-b pb-2 dark:border-zinc-700">Order Summary</h3>
                
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="font-medium dark:text-white">{{ $transaction->product_name_snapshot }}</p>
                        @if($transaction->product_attachment_snapshot)
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($transaction->product_attachment_snapshot) }}" target="_blank" class="text-xs text-blue-500 hover:underline">
                                Download Snapshot File
                            </a>
                        @else
                            <span class="text-xs text-zinc-500">No file attached</span>
                        @endif
                    </div>
                    <span class="dark:text-zinc-200">£{{ number_format($transaction->price, 2) }}</span>
                </div>

                <div class="border-t dark:border-zinc-700 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-zinc-500 dark:text-zinc-400">
                        <span>Subtotal</span>
                        <span>£{{ number_format($transaction->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-zinc-500 dark:text-zinc-400">
                        <span>VAT</span>
                        <span>£{{ number_format($transaction->vat_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold dark:text-white pt-2 border-t dark:border-zinc-800">
                        <span>Total</span>
                        <span>£{{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
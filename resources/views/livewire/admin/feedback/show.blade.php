<div class="p-6 space-y-6 bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Feedback Details</h2>
            <p class="mt-1 text-sm text-gray-500">View details of the user feedback.</p>
        </div>
        <a href="{{ route('admin.feedback.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-btn-primary-ring focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Back to List
        </a>
    </div>

    <!-- Content -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-6 space-y-6">
            <!-- User Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">User
                        Name</label>
                    <p class="text-base font-medium text-black">{{ $feedback->user->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">User
                        Email</label>
                    <p class="text-base font-medium text-black">{{ $feedback->user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Received
                        At</label>
                    <p class="text-base text-black">{{ $feedback->created_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Message -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Message</label>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $feedback->message }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
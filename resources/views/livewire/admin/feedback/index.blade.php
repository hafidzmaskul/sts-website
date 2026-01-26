<div class="p-6 space-y-6 bg-white">
    <div class="mb-6 bg-white shadow-sm rounded-lg border border-gray-200 p-4">
        <h3 class="text-sm font-medium text-black mb-4">Filter Options</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Search -->
            <div class="md:col-span-12">
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search by message, name or email..."
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-zinc-50 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">No</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Email</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Feedback</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feedbacks as $index => $feedback)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"
                            wire:key="feedback-{{ $feedback->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feedbacks->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $feedback->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feedback->user->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ Str::limit($feedback->message, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <a href="{{ route('admin.feedback.show', $feedback) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No feedback found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
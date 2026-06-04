<div class="p-6 space-y-6 bg-white">
    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Messages</h3>
            <p class="mt-2 text-3xl font-semibold text-black">{{ $totalSubmissions }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500">Today's Messages</h3>
            <p class="mt-2 text-3xl font-semibold text-black">{{ $todaySubmissions }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500">Top Subjects</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subjectCounts as $subject)
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-700 truncate max-w-[70%]">{{ $subject->subject }}</span>
                        <span class="font-semibold text-black">{{ $subject->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 bg-white shadow-sm rounded-lg border border-gray-200 p-4">
        <h3 class="text-sm font-medium text-black mb-4">Filter Options</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Search -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Name, Email or Subject"
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring py-2 px-3">
            </div>

            <!-- Dates -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Start Date</label>
                <input wire:model.live="dateStart" type="date"
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring py-2 px-3">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">End Date</label>
                <input wire:model.live="dateEnd" type="date"
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring py-2 px-3">
            </div>

            <!-- Subject Filter -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Subject</label>
                <select wire:model.live="subjectFilter"
                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring py-2 px-3">
                    <option value="">All Subjects</option>
                    @foreach($subjectCounts as $subject)
                        <option value="{{ $subject->subject }}">{{ Str::limit($subject->subject, 30) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Button -->
            <div class="md:col-span-2 flex justify-end">
                <button wire:click="resetFilters"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-zinc-50 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">No</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Date</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Email</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Phone</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Subject</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $index => $submission)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out" wire:key="submission-{{ $submission->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submissions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $submission->first_name }} {{ $submission->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $submission->subject }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="viewDetails({{ $submission->id }})" class="text-black hover:text-indigo-600 transition-colors" title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    @can('contact-submissions.delete')
                                        <button wire:confirm="Are you sure you want to delete this submission?" wire:click="delete({{ $submission->id }})" class="text-black hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $submissions->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:key="detail-modal-{{ $selectedSubmissionId }}">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDetailModal"></div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        @if($selectedSubmission)
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-black" id="modal-title">
                                    Submission Details
                                </h3>
                                <div class="mt-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase">Received At</label>
                                        <p class="text-sm text-black">{{ $selectedSubmission->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase">Name</label>
                                            <p class="text-sm text-black">{{ $selectedSubmission->first_name }} {{ $selectedSubmission->last_name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase">Phone</label>
                                            <p class="text-sm text-black">{{ $selectedSubmission->phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase">Email</label>
                                        <p class="text-sm text-black">{{ $selectedSubmission->email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase">Subject</label>
                                        <p class="text-sm text-black">{{ $selectedSubmission->subject }}</p>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 mt-3">
                                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Message</label>
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 p-3 rounded-md">{{ $selectedSubmission->message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-4 text-center">
                            <span class="text-gray-500">Loading details...</span>
                        </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeDetailModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-black hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

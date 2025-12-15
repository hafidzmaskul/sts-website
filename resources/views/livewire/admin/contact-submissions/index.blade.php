<div class="p-6 space-y-6 dark:bg-zinc-900">
    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Messages</h3>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-zinc-100">{{ $totalSubmissions }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-zinc-400">Today's Messages</h3>
            <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-zinc-100">{{ $todaySubmissions }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-zinc-400">Top Subjects</h3>
            <ul class="mt-2 space-y-1">
                @foreach($subjectCounts as $subject)
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-700 dark:text-zinc-300 truncate max-w-[70%]">{{ $subject->subject }}</span>
                        <span class="font-semibold text-gray-900 dark:text-zinc-100">{{ $subject->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 p-4">
        <h3 class="text-sm font-medium text-gray-900 dark:text-zinc-100 mb-4">Filter Options</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Search -->
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1 uppercase tracking-wider">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Name, Email or Subject"
                    class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
            </div>
            
            <!-- Dates -->
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1 uppercase tracking-wider">Start Date</label>
                <input wire:model.live="dateStart" type="date"
                    class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1 uppercase tracking-wider">End Date</label>
                <input wire:model.live="dateEnd" type="date"
                    class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
            </div>

            <!-- Subject Filter -->
             <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 mb-1 uppercase tracking-wider">Subject</label>
                <select wire:model.live="subjectFilter" 
                    class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    <option value="">All Subjects</option>
                    @foreach($subjectCounts as $subject)
                        <option value="{{ $subject->subject }}">{{ Str::limit($subject->subject, 30) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Button -->
            <div class="md:col-span-2 flex justify-end">
                <button wire:click="resetFilters" 
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 dark:hover:bg-zinc-600">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">No</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Date</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Email</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Phone</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Subject</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
                    @forelse($submissions as $index => $submission)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition duration-150 ease-in-out" wire:key="submission-{{ $submission->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                {{ $submissions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                {{ $submission->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-zinc-100">
                                {{ $submission->first_name }} {{ $submission->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                {{ $submission->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                {{ $submission->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-zinc-100">
                                {{ $submission->subject }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <button wire:click="viewDetails({{ $submission->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Detail
                                </button>
                                @can('contact-submissions.delete')
                                    <button wire:click="delete({{ $submission->id }})"
                                        wire:confirm="Are you sure you want to delete this submission?"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">
                                No submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
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
                <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        @if($selectedSubmission)
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-zinc-100" id="modal-title">
                                    Submission Details
                                </h3>
                                <div class="mt-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Received At</label>
                                        <p class="text-sm text-gray-900 dark:text-zinc-100">{{ $selectedSubmission->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                         <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Name</label>
                                            <p class="text-sm text-gray-900 dark:text-zinc-100">{{ $selectedSubmission->first_name }} {{ $selectedSubmission->last_name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Phone</label>
                                            <p class="text-sm text-gray-900 dark:text-zinc-100">{{ $selectedSubmission->phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Email</label>
                                        <p class="text-sm text-gray-900 dark:text-zinc-100">{{ $selectedSubmission->email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Subject</label>
                                        <p class="text-sm text-gray-900 dark:text-zinc-100">{{ $selectedSubmission->subject }}</p>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 mt-3">
                                        <label class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase mb-1">Message</label>
                                        <p class="text-sm text-gray-700 dark:text-zinc-300 whitespace-pre-wrap bg-gray-50 dark:bg-zinc-900 p-3 rounded-md">{{ $selectedSubmission->message }}</p>
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
                    <div class="bg-gray-50 dark:bg-zinc-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeDetailModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-600 dark:hover:bg-zinc-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
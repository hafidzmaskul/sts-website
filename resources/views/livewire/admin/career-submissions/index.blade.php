<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Job Applications
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by applicant name or email..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Applicant</th>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Applied Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3">
                                <div class="font-medium dark:text-white">{{ $submission->full_name }}</div>
                                <div class="text-xs text-zinc-500">{{ $submission->email }}</div>
                                <div class="text-xs text-zinc-500">{{ $submission->mobile_phone }}</div>
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                {{ $submission->career->title ?? 'Unknown Job' }}
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                {{ $submission->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($submission->status == 'pending')
                                    <span class="px-2 py-0.5 rounded border text-xs text-yellow-700 border-yellow-400 bg-yellow-100 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-blue-700 border-blue-400 bg-blue-100 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-200">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="showDetails({{ $submission->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-700"
                                    >View</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $submissions->links() }}</div>

        <!-- View Details Modal -->
        @if($showModal && $selectedSubmission)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6 dark:bg-zinc-900 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold dark:text-white">Application Details</h2>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 text-2xl leading-none">&times;</button>
                </div>
                
                <div class="space-y-6">
                    <!-- Header Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800">
                        <div>
                            <label class="block text-xs font-medium uppercase text-zinc-500 dark:text-zinc-400">Applying For</label>
                            <p class="font-semibold dark:text-white">{{ $selectedSubmission->career->title ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase text-zinc-500 dark:text-zinc-400">Submitted On</label>
                            <p class="dark:text-zinc-300">{{ $selectedSubmission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Applicant Info -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium dark:text-white border-b pb-2 dark:border-zinc-700">Candidate Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-zinc-500">Full Name</span>
                                <span class="dark:text-zinc-200">{{ $selectedSubmission->full_name }}</span>
                            </div>
                            <div>
                                <span class="block text-zinc-500">Email</span>
                                <span class="dark:text-zinc-200">{{ $selectedSubmission->email }}</span>
                            </div>
                            <div>
                                <span class="block text-zinc-500">Mobile Phone</span>
                                <span class="dark:text-zinc-200">{{ $selectedSubmission->mobile_phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-2">
                        <h3 class="text-sm font-medium dark:text-white border-b pb-2 dark:border-zinc-700">Message / Cover Letter</h3>
                        <div class="p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm whitespace-pre-wrap dark:text-zinc-300">
                            {{ $selectedSubmission->message ?: 'No message provided.' }}
                        </div>
                    </div>

                    <!-- Resume -->
                    <div class="space-y-2">
                        <h3 class="text-sm font-medium dark:text-white border-b pb-2 dark:border-zinc-700">Resume / CV</h3>
                        @if($selectedSubmission->resume_path)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium dark:text-zinc-200">Resume File</span>
                                </div>
                                <a 
                                    href="{{ Storage::url($selectedSubmission->resume_path) }}" 
                                    target="_blank"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Download
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-zinc-500">No resume attached.</p>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between mt-8 pt-4 border-t dark:border-zinc-700">
                    <button
                        type="button"
                        wire:click="delete({{ $selectedSubmission->id }})"
                        wire:confirm="Are you sure you want to delete this application? This will also delete the resume file."
                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                    >Delete Application</button>
                    
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-medium"
                    >Close</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
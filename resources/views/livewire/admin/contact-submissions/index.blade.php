<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Contact Submissions
            </h1>
            </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name, email, or subject..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Received At</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $submission->name }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $submission->email }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ \Illuminate\Support\Str::limit($submission->subject, 50) }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $submission->created_at->format('M d, Y - H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="showDetails({{ $submission->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                    >View</button>
                                    @can('contact-submissions.delete')
                                    <button
                                        wire:click="delete({{ $submission->id }})"
                                        wire:confirm="Are you sure you want to delete this submission?"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                    >Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $submissions->links() }}</div>

        @if($showModal && $selectedSubmission)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6 dark:bg-zinc-900 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">View Submission</h2>
                    <button wire:click="closeModal" class="dark:text-zinc-300">&times;</button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Name</label>
                        <p class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">{{ $selectedSubmission->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Email</label>
                        <p class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">{{ $selectedSubmission->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Subject</label>
                        <p class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">{{ $selectedSubmission->subject }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Message</label>
                        <p class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 whitespace-pre-wrap">{{ $selectedSubmission->message }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-6">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                    >Close</button>
                    @can('contact-submissions.delete')
                    <button
                        type="button"
                        wire:click="delete({{ $selectedSubmission->id }})"
                        wire:confirm="Are you sure you want to delete this submission?"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white dark:bg-red-700 transition-colors"
                    >Delete</button>
                    @endcan
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
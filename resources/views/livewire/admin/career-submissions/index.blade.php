<div style="color: #000;">
    <header class="sticky top-0 z-10 border-b border-zinc-200 backdrop-blur" style="background: #fff;">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold">
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
                class="w-full md:w-80 rounded-lg border !border-[#AEAEAE] px-3 py-2"
                style="color: #000; border-color: #AEAEAE; background: #fff; ::placeholder{color: #D2D2D2;}"
            />
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-zinc-200">
            <table class="min-w-full text-sm" style="color: #000;">
                <thead style="background: #fff;">
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
                        <tr class="border-t border-zinc-200">
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $submission->full_name }}</div>
                                <div class="text-xs">{{ $submission->email }}</div>
                                <div class="text-xs">{{ $submission->mobile_phone }}</div>
                            </td>
                            <td class="px-4 py-3">
                                {{ $submission->career->title ?? 'Unknown Job' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $submission->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($submission->status == 'pending')
                                    <span class="px-2 py-0.5 rounded border text-xs" style="border-color:#FFD700; color:#FFD700; background: #fff;">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs" style="border-color:#0079C2;color:#0079C2;background:#fff;">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="showDetails({{ $submission->id }})"
                                        class="px-3 py-1.5 rounded border border-[#0079C2] text-[#0079C2] bg-white hover:cursor-pointer transition"
                                        style="display: flex; align-items: center; color: #0079C2;"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color: #000000;" viewBox="0 0 1200 1200">
                                            <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="overflow-x-auto">
            {{ $submissions->links() }}
        </div>

        <!-- View Details Modal -->
        @if($showModal && $selectedSubmission)
        <div class="fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.4);">
            <div class="w-full max-w-2xl rounded-2xl p-6 max-h-[90vh] overflow-y-auto border border-zinc-200 bg-white" style="color: #000;">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold">Application Details</h2>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-800 text-2xl leading-none" style="background:none; border:none;color:#000;">&times;</button>
                </div>

                <div class="space-y-6">
                    <!-- Header Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 rounded-lg" style="background: #fff; border: 1px solid #EEEEEE;">
                        <div>
                            <label class="block text-xs font-medium uppercase" style="color:#AEAEAE;">Applying For</label>
                            <p class="font-semibold">{{ $selectedSubmission->career->title ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase" style="color:#AEAEAE;">Submitted On</label>
                            <p>{{ $selectedSubmission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Applicant Info -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium border-b pb-2" style="border-color: #EEEEEE;">Candidate Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block">Full Name</span>
                                <span>{{ $selectedSubmission->full_name }}</span>
                            </div>
                            <div>
                                <span class="block">Email</span>
                                <span>{{ $selectedSubmission->email }}</span>
                            </div>
                            <div>
                                <span class="block">Mobile Phone</span>
                                <span>{{ $selectedSubmission->mobile_phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-2">
                        <h3 class="text-sm font-medium border-b pb-2" style="border-color: #EEEEEE;">Message / Cover Letter</h3>
                        <div class="p-4 rounded-lg border border-zinc-200 text-sm whitespace-pre-wrap" style="background: #fff;">
                            {{ $selectedSubmission->message ?: 'No message provided.' }}
                        </div>
                    </div>

                    <!-- Resume -->
                    <div class="space-y-2">
                        <h3 class="text-sm font-medium border-b pb-2" style="border-color: #EEEEEE;">Resume / CV</h3>
                        @if($selectedSubmission->resume_path)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-zinc-200" style="background: #fff;">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium">Resume File</span>
                                </div>
                                <a
                                    href="{{ Storage::url($selectedSubmission->resume_path) }}"
                                    target="_blank"
                                    class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition text-sm font-medium"
                                >
                                    Download
                                </a>
                            </div>
                        @else
                            <p class="text-sm">No resume attached.</p>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between mt-8 pt-4 border-t border-zinc-200">
                    <button wire:confirm="Are you sure you want to delete this application? This will also delete the resume file." wire:click="delete({{ $selectedSubmission->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>

                    <button
                        type="button"
                        wire:click="closeModal"
                        class="border border-[#0079C2] text-[#0079C2] px-4 py-2 rounded-lg font-medium hover:cursor-pointer transition"
                        style="background: white;"
                    >Close</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

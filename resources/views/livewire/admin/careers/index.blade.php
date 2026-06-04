<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                Careers
            </h1>
            <a href="{{ route('admin.careers.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Job
        </a>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by title or department..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 bg-white text-black border-zinc-300"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border bg-white border-zinc-200">
            <table class="min-w-full text-sm text-black">
                <thead class="bg-zinc-50 text-left text-black">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">Level / Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($careers as $career)
                        <tr class="border-t border-zinc-200">
                            <td class="px-4 py-3 font-medium text-black">{{ $career->title }}</td>
                            <td class="px-4 py-3 text-zinc-700">{{ $career->department }}</td>
                            <td class="px-4 py-3 text-zinc-700 text-xs">
                                <span class="px-2 py-0.5 rounded bg-zinc-100">{{ $career->level }}</span>
                                <span class="px-2 py-0.5 rounded bg-zinc-100 ml-1">{{ $career->employment_type }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($career->status)
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100">Published</span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.careers.edit', $career) }}" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <button wire:confirm="Delete this job posting?" wire:click="delete({{ $career->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-zinc-500">No careers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $careers->links() }}</div>
    </div>
</div>

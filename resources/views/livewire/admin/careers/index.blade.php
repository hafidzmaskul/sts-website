<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                Careers
            </h1>
            <a
                href="{{ route('admin.careers.create') }}"
                wire:navigate
                class="px-4 py-2 rounded-lg bg-zinc-900 text-white transition-colors"
            >
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
                                    <a
                                        href="{{ route('admin.careers.edit', $career) }}"
                                        wire:navigate
                                        class="px-3 py-1.5 rounded border border-zinc-300 bg-white text-black"
                                    >Edit</a>
                                    <button
                                        wire:click="delete({{ $career->id }})"
                                        wire:confirm="Delete this job posting?"
                                        class="px-3 py-1.5 rounded border text-red-600 border-zinc-300 bg-white"
                                    >Delete</button>
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

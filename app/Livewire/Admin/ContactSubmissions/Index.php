<?php

namespace App\Livewire\Admin\ContactSubmissions;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateStart = null;
    public $dateEnd = null;
    public $subjectFilter = '';

    public $showDetailModal = false;
    public $selectedSubmissionId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'dateStart' => ['except' => null],
        'dateEnd' => ['except' => null],
        'subjectFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateStart', 'dateEnd', 'subjectFilter']);
        $this->resetPage();
    }

    public function viewDetails($id)
    {
        $this->selectedSubmissionId = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedSubmissionId = null;
    }

    public function delete($id)
    {
        if (!auth()->user()->can('contact-submissions.delete')) {
            abort(403);
        }

        ContactSubmission::findOrFail($id)->delete();
        $this->dispatch('notify', 'Submission deleted successfully.');
    }

    public function render()
    {
        if (!auth()->user()->can('contact-submissions.view')) {
            abort(403);
        }

        $query = ContactSubmission::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateStart) {
            $query->whereDate('created_at', '>=', $this->dateStart);
        }

        if ($this->dateEnd) {
            $query->whereDate('created_at', '<=', $this->dateEnd);
        }

        if ($this->subjectFilter) {
            $query->where('subject', 'like', '%' . $this->subjectFilter . '%');
        }

        $submissions = $query->latest()->paginate(10);

        // Stats Calculation
        $totalSubmissions = ContactSubmission::count();
        $subjectCounts = ContactSubmission::select('subject', \DB::raw('count(*) as total'))
            ->groupBy('subject')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        // Additional Stat: Submissions Today
        $todaySubmissions = ContactSubmission::whereDate('created_at', now())->count();

        $selectedSubmission = $this->selectedSubmissionId
            ? ContactSubmission::find($this->selectedSubmissionId)
            : null;

        return view('livewire.admin.contact-submissions.index', [
            'submissions' => $submissions,
            'totalSubmissions' => $totalSubmissions,
            'todaySubmissions' => $todaySubmissions,
            'subjectCounts' => $subjectCounts,
            'selectedSubmission' => $selectedSubmission,
        ])->title('Contact Submissions');
    }
}

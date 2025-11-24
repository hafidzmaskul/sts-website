<?php

namespace App\Livewire\Admin\CareerSubmissions;

use App\Models\CareerSubmission;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Job Applications')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    public $showModal = false;
    public $selectedSubmission;

    public function showDetails($id)
    {
        $this->selectedSubmission = CareerSubmission::with('career')->findOrFail($id);
        
        if ($this->selectedSubmission->status === 'pending') {
            $this->selectedSubmission->update(['status' => 'reviewed']);
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSubmission = null;
    }

    public function delete($id)
    {
        $submission = CareerSubmission::findOrFail($id);
        
        if ($submission->resume_path && Storage::disk('public')->exists($submission->resume_path)) {
            Storage::disk('public')->delete($submission->resume_path);
        }
        
        $submission->delete();
        
        $this->dispatch('alert', type: 'success', message: 'Application deleted successfully.');
        $this->closeModal();
    }

    public function render()
    {
        $submissions = CareerSubmission::with('career')
            ->where('full_name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.career-submissions.index', [
            'submissions' => $submissions,
        ]);
    }
}
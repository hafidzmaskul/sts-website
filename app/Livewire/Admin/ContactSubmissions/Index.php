<?php

namespace App\Livewire\Admin\ContactSubmissions;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Contact Submissions')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $selectedSubmission;

    public function showDetails($id)
    {
        $this->selectedSubmission = ContactSubmission::findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSubmission = null;
    }

    public function delete($id)
    {
        if (Gate::denies('contact-submissions.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete submissions.');
            return;
        }

        ContactSubmission::findOrFail($id)->delete();
        $this->dispatch('alert', type: 'success', message: 'Submission deleted successfully.');
        $this->closeModal(); // Close modal if it was open for this item
    }

    public function render()
    {
        $submissions = ContactSubmission::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('subject', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.contact-submissions.index', [
            'submissions' => $submissions,
        ]);
    }
}
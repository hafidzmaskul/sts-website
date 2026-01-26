<?php

namespace App\Livewire\Admin\Feedback;

use App\Models\Feedback;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $feedbacks = Feedback::with('user')
            ->when($this->search, function ($query) {
                $query->where('message', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.feedback.index', [
            'feedbacks' => $feedbacks
        ]);
    }
}

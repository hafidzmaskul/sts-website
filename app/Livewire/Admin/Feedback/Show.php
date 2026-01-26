<?php

namespace App\Livewire\Admin\Feedback;

use App\Models\Feedback;
use Livewire\Component;

class Show extends Component
{
    public Feedback $feedback;

    public function mount(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function render()
    {
        return view('livewire.admin.feedback.show');
    }
}

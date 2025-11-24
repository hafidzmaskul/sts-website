<?php

namespace App\Livewire\Admin\Careers;

use App\Models\Career;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Careers')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        if (session()->has('alert')) {
            $alert = session('alert');
            $this->dispatch('alert', type: $alert['type'], message: $alert['message']);
        }
    }

    public function delete($id)
    {
        Career::findOrFail($id)->delete();
        $this->dispatch('alert', type: 'success', message: 'Career deleted successfully.');
    }

    public function render()
    {
        $careers = Career::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('department', 'like', '%' . $this->search . '%')
            ->orderBy('sequence', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.careers.index', [
            'careers' => $careers,
        ]);
    }
}
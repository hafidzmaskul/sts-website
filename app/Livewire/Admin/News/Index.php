<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[Title('News')]
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
        if (Gate::denies('news.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete news.');
            return;
        }

        $news = News::findOrFail($id);
        if ($news->image) {
            if (Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
        }
        $news->delete();
        $this->dispatch('alert', type: 'success', message: 'News post deleted successfully.');
    }

    public function render()
    {
        $news = News::with(['user', 'categories'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.news.index', [
            'news' => $news,
        ]);
    }
}
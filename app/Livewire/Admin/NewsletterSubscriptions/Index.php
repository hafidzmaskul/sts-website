<?php

namespace App\Livewire\Admin\NewsletterSubscriptions;

use App\Models\NewsletterSubscription;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Newsletter Subscriptions')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        if (Gate::denies('newsletter-subscriptions.delete')) {
            $this->dispatch('alert', type: 'error', message: 'You do not have permission to delete subscriptions.');
            return;
        }

        NewsletterSubscription::findOrFail($id)->delete();
        $this->dispatch('alert', type: 'success', message: 'Subscription deleted successfully.');
    }

    public function render()
    {
        $subscriptions = NewsletterSubscription::where('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.newsletter-subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
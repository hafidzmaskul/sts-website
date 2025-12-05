<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\NewsletterSubscription;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $this->authorize('newsletter-subscriptions.delete');
        $subscription = NewsletterSubscription::findOrFail($id);
        $subscription->delete();

        $this->dispatch('notify', type: 'success', message: 'Subscription deleted successfully.');
    }

    public function render()
    {
        $this->authorize('newsletter-subscriptions.view');

        $subscriptions = NewsletterSubscription::query()
            ->when($this->search, function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.newsletter.index', [
            'subscriptions' => $subscriptions,
        ])->title('Newsletter Subscriptions');
    }
}

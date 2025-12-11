<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\NewsletterSubscription;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

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

    public function exportCsv()
    {
        $this->authorize('newsletter-subscriptions.view');

        $subscriptions = NewsletterSubscription::query()
            ->when($this->search, function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter_subscriptions.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return Response::stream(function () use ($subscriptions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Source', 'Subscribed At']);

            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->id,
                    $subscription->email,
                    $subscription->source ?? 'N/A',
                    $subscription->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        }, 200, $headers);
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

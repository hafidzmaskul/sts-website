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

    public function exportCsv()
    {
        $fileName = 'newsletter_subscriptions_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email', 'Status', 'Subscribed At']);

            NewsletterSubscription::chunk(100, function ($subscriptions) use ($handle) {
                foreach ($subscriptions as $subscription) {
                    fputcsv($handle, [
                        $subscription->id,
                        $subscription->email,
                        $subscription->is_subscribe ? 'Subscribed' : 'Unsubscribed',
                        $subscription->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
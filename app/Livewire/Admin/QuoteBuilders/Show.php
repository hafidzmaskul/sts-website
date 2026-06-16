<?php

namespace App\Livewire\Admin\QuoteBuilders;

use App\Models\QuoteBuilder;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    /**
     * The QuoteBuilder model instance.
     */
    public QuoteBuilder $quoteBuilder;

    /**
     * Controls the visibility of the "Login as User" modal.
     */
    public bool $showLoginModal = false;

    /**
     * Mount the component and eager load relations.
     */
    public function mount(QuoteBuilder $quoteBuilder): void
    {
        $this->quoteBuilder = $quoteBuilder->load(['user.customer.company', 'products.images']);
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): View
    {
        return view('livewire.admin.quote-builders.show')
            ->title('Quote Builder Details');
    }
}

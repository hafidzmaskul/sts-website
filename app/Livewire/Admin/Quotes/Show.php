<?php

namespace App\Livewire\Admin\Quotes;

use App\Models\Quote;
use Livewire\Component;

class Show extends Component
{
    public Quote $quote;

    public function mount(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function render()
    {
        return view('livewire.admin.quotes.show')
            ->title('Quote Details');
    }
}

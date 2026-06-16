<?php

namespace App\Livewire\Admin\Quotes;

use App\Models\Cart;
use App\Models\Quote;
use App\Models\QuoteBuilder;
use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public Quote $quote;

    public ?User $registeredUser = null;

    public $quoteBuilders = [];

    public $cartItems = [];

    public function mount(Quote $quote)
    {
        $this->quote = $quote;

        // Find the registered user by email
        $this->registeredUser = User::where('email', $quote->email)
            ->with(['customer.company'])
            ->first();

        if ($this->registeredUser) {
            $this->quoteBuilders = QuoteBuilder::where('user_id', $this->registeredUser->id)
                ->with(['products.images'])
                ->get();

            $this->cartItems = Cart::where('user_id', $this->registeredUser->id)
                ->with(['product.images'])
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.quotes.show')
            ->title('Quote Details');
    }
}

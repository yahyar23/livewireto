<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class Navbar extends Component
{
    public $categories;
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->categories = Category::with('children.children')
            ->whereNull('parent_id')
            ->get();

        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $this->cartCount = array_sum(array_column(session()->get('cart', []), 'quantity'));
    }

    public function render()
    {
        return view('livewire.frontend.navbar');
    }
}

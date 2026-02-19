<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class Cart extends Component
{
    public $cart = [];
    public $showCart = false;
    public $addedMessage = '';

    protected $listeners = ['addToCart'];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function addToCart($productId)
    {
        $product = Product::with('variants', 'images')->findOrFail($productId);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {

            // زيادة الكمية إذا المنتج موجود مسبقاً
            $cart[$productId]['quantity'] += 1;

        } else {

            // إضافة منتج جديد مع ID مهم جداً
            $cart[$productId] = [
                'id'       => $product->id, // 🔥 هذا كان ناقص
                'name'     => $product->name,
                'price'    => $product->variants->first()->price ?? 0,
                'image'    => $product->images->first()->image_path ?? null,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        $this->cart = $cart;

        $this->addedMessage = $product->name . ' تمت إضافته للسلة';
        $this->showCart = true;

        $this->emit('cartUpdated');
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);
        $this->cart = $cart;

        $this->emit('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart');
    }
    public function closeCart()
{
    $this->showCart = false;
}

}

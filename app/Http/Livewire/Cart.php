<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class Cart extends Component
{
    public $cart = [];
    public $showCart = false;
    public $message = ''; // استخدمنا message بدل addedMessage لتعميمها
public $messageType = 'success'; // ← هذا هو المهم

    protected $listeners = ['addToCart'];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    // إضافة المنتج
    public function addToCart($productId)
    {
        $product = Product::with('variants', 'images')->findOrFail($productId);
        $cart = session()->get('cart', []);
$product = Product::with('variants', 'images')->findOrFail($productId);

$variant = $product->variants->first();  // 🔥 هذا هو الحل

if (!$variant) {
    return;
}

$variantId = $variant->id;
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'id'       => $product->id,
                 'variant_id'  => $variantId,   
                'name'     => $product->name,
                'price'    => $variant->price,
                'image'    => $product->images->first()->image_path ?? null,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->cart = $cart;

        $this->message = $product->name . ' تمت إضافته للسلة';
        $this->showCart = true;
$this->emit('productAdded', $product->name);
$this->emit('cartUpdated', 'added');

    }

    // حذف المنتج
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $deletedName = $cart[$productId]['name'] ?? 'المنتج';
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->cart = $cart;

            $this->message = $deletedName . ' تم حذفه من السلة';
        }

       $this->emit('productRemoved', $deletedName);
$this->emit('cartUpdated', 'removed');

    }

    public function closeCart()
    {
        $this->showCart = false;
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.cart');
    }
}

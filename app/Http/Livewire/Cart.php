<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class Cart extends Component
{
    public $cart = [];
    public $showCart = false;
    public $message = ''; 
    public $messageType = 'success';

    // استقبال الحدث مع VariantId
    protected $listeners = ['addToCart'];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    /**
     * إضافة منتج إلى السلة مع دعم Variants
     * $productId → المنتج
     * $variantId → المقاس / النوع
     */
    public function addToCart($productId, $variantId = null)
    {
        $product = Product::with('variants', 'images')->findOrFail($productId);
        $cart = session()->get('cart', []);

        // اختيار Variant
        if ($variantId) {
            $variant = $product->variants->where('id', $variantId)->first();
        } else {
            $variant = $product->variants->first();
        }

        if (!$variant) {
            $this->dispatchBrowserEvent('notify', ['message' => 'المنتج لا يحتوي على مقاس أو النوع المحدد']);
            return;
        }

        // مفتاح السلة يكون المنتج + variantId لضمان التفريق بين Variants
        $cartKey = $product->id . '-' . $variant->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += 1;
        } else {
            $cart[$cartKey] = [
                'id'         => $product->id,
                'variant_id' => $variant->id,
                'name'       => $product->name,
                'variant'    => $variant->name ?? '', // اسم المقاس/النوع
                'price'      => $variant->price,
                'image'      => $product->images->first()->image_path ?? null,
                'quantity'   => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->showCart = true;

        $this->message = $product->name . ' (' . ($variant->name ?? '') . ') تمت إضافته للسلة';
        $this->emit('productAdded', $product->name);
        $this->emit('cartUpdated', 'added');
    }

    /**
     * حذف منتج من السلة حسب Variant
     */
    public function removeFromCart($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $deletedName = $cart[$cartKey]['name'] ?? 'المنتج';
            $deletedVariant = $cart[$cartKey]['variant'] ?? '';
            unset($cart[$cartKey]);

            session()->put('cart', $cart);
            $this->cart = $cart;

            $this->message = $deletedName . ' (' . $deletedVariant . ') تم حذفه من السلة';
            $this->emit('productRemoved', $deletedName);
            $this->emit('cartUpdated', 'removed');
        }
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
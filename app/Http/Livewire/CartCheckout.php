<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;

class CartCheckout extends Component
{
    public $cart = [];

    // بيانات العميل
    public $name;
    public $phone;
    public $address;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    /**
     * تحديث كمية منتج معين في السلة باستخدام cartKey (منتج + variant)
     */
    public function updateQuantity($cartKey, $quantity)
    {
        $quantity = max(1, (int)$quantity);

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
        }
    }

    /**
     * حذف منتج من السلة باستخدام cartKey
     */
    public function remove($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            unset($this->cart[$cartKey]);
            session()->put('cart', $this->cart);
        }

        $this->cart = session()->get('cart', []);
    }

    /**
     * حساب الإجمالي مع Variants
     */
    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->cart as $item) {
            $price = isset($item['price']) ? (float)$item['price'] : 0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $total += $price * $quantity;
        }

        return $total;
    }

    /**
     * إتمام الطلب
     */
    public function placeOrder()
    {
        if (empty($this->cart)) {
            $this->dispatchBrowserEvent('notify', ['message' => 'السلة فارغة']);
            return;
        }

        // التحقق من البيانات
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        // 1️⃣ إنشاء الطلب
        $order = Order::create([
            'user_id'       => auth()->check() ? auth()->id() : null,
            'visitor_name'  => $this->name,
            'visitor_phone' => $this->phone,
            'address'       => $this->address,
            'total'         => $this->calculateTotal(),
            'status'        => 'pending',
        ]);

        // 2️⃣ حفظ المنتجات مع variant_id
        foreach ($this->cart as $cartKey => $item) {

            if (!isset($item['id'])) continue;

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['id'],
                'variant_id'   => $item['variant_id'] ?? null,
                'product_name' => $item['name'] ?? '',
                'variant_name' => $item['variant'] ?? '', // اسم المقاس/النوع
                'price'        => $item['price'] ?? 0,
                'quantity'     => $item['quantity'] ?? 1,
            ]);
        }

        // 3️⃣ مسح السلة
        session()->forget('cart');
        $this->cart = [];

        $this->reset(['name', 'phone', 'address']);

        $this->dispatchBrowserEvent('notify', [
            'message' => 'تم إتمام الطلب بنجاح'
        ]);
    }

    public function render()
    {
        return view('livewire.cart-checkout', [
            'total' => $this->calculateTotal(),
            'cart'  => $this->cart,
        ])->layout('layouts.app');
    }
}
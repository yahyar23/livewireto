<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Product;

class ProductDetails extends Component
{
    public $product;
    public $selectedVariantId = null;

    // نستخدم slug بدل id
    public function mount($slug)
    {
        // جلب المنتج باستخدام slug
        $this->product = Product::with('images', 'variants')
            ->where('slug', $slug)
            ->firstOrFail();

        // تحديد أول Variant تلقائيًا إذا موجود
        if ($this->product->variants->count() > 0) {
            $this->selectedVariantId = $this->product->variants->first()->id;
        }
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariantId = $variantId;
    }

    public function addToCart()
    {
        if (!$this->selectedVariantId) {
            $this->dispatchBrowserEvent('notify', ['message' => 'يرجى اختيار المقاس أو النوع']);
            return;
        }

        // نرسل إلى كومبونت Cart
        $this->emit('addToCart', $this->product->id, $this->selectedVariantId);

        $this->dispatchBrowserEvent('showSuccessMessage', [
            'message' => 'تم إضافة المنتج إلى السلة بنجاح'
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.product-details')
            ->layout('layouts.app');
    }
}

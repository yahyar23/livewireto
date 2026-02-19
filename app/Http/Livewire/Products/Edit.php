<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImages;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public $name;
    public $category_id;
    public $description;
    public $images = [];

    public $has_variants = false;
    public $price;
    public $stock;
    public $variants = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
    ];

    // تعديل mount ليأخذ Product مباشرة
    public function mount(Product $product)
    {
        $this->product = $product->load(['variants', 'images']);

        $this->name = $this->product->name;
        $this->category_id = $this->product->category_id;
        $this->description = $this->product->description;
        $this->has_variants = $this->product->has_variants;

        if (!$this->has_variants && $this->product->variants->first()) {
            $this->price = $this->product->variants->first()->price;
            $this->stock = $this->product->variants->first()->stock;
        } else {
            foreach ($this->product->variants as $variant) {
                $this->variants[] = [
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                ];
            }
        }
    }

    public function addVariant()
    {
        $this->variants[] = [
            'sku' => '',
            'price' => '',
            'stock' => '',
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->validate();

        $this->product->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'category_id' => $this->category_id,
            'description' => $this->description,
            'has_variants' => $this->has_variants,
        ]);

        // حفظ الصور الجديدة فقط
        foreach ($this->images as $image) {
            $path = $image->store('products', 'public');
            ProductImages::create([
                'product_id' => $this->product->id,
                'image_path' => $path,
            ]);
        }

        // تحديث Variants
        if (!$this->has_variants) {
            $variant = $this->product->variants->first();
            if ($variant) {
                $variant->update([
                    'price' => $this->price,
                    'stock' => $this->stock,
                ]);
            } else {
                ProductVariant::create([
                    'product_id' => $this->product->id,
                    'sku' => strtoupper(uniqid('SKU-')),
                    'price' => $this->price,
                    'stock' => $this->stock,
                ]);
            }
        } else {
            foreach ($this->variants as $index => $variantData) {
                $variant = $this->product->variants[$index] ?? null;
                if ($variant) {
                    $variant->update($variantData);
                } else {
                    ProductVariant::create(array_merge($variantData, ['product_id' => $this->product->id]));
                }
            }
        }

        session()->flash('success', 'تم تحديث المنتج بنجاح');
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.edit', [
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }
}

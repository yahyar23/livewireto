<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImages;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

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

    // توليد slug فريد
    $slug = Str::slug($this->name);
    $originalSlug = $slug;
    $count = 1;

    while (Product::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $product = Product::create([
        'name' => $this->name,
        'slug' => $slug,
        'category_id' => $this->category_id,
        'description' => $this->description,
        'has_variants' => $this->has_variants,
    ]);

    // حفظ الصور
    foreach ($this->images as $image) {
        $path = $image->store('products', 'public');

       ProductImages::create([
            'product_id' => $product->id,
            'image_path' => $path,
        ]);
    }

    // إذا لا يحتوي Variants
    if (!$this->has_variants) {
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => strtoupper(uniqid('SKU-')),
            'price' => $this->price,
            'stock' => $this->stock,
        ]);
    } else {
        foreach ($this->variants as $variant) {
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variant['sku'] ?: strtoupper(uniqid('SKU-')),
                'price' => $variant['price'],
                'stock' => $variant['stock'],
            ]);
        }
    }

    session()->flash('success', 'تم إنشاء المنتج بنجاح');

    return redirect()->route('products.index');
}


    public function render()
    {
        return view('livewire.products.create', [
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }
}

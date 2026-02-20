<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class Products extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public $search = '';
    public $categories;
    public $selectedCategory = null;
    public $cartCount = 0;
    public $alertMessage = null;

    protected $listeners = ['cartUpdated' => 'handleCartUpdated'];

    public function mount()
    {
        // تحميل الأقسام
        $this->categories = Category::with('children.children')
            ->whereNull('parent_id')
            ->get();

        // استقبال category من الرابط
        if (request()->has('category')) {
            $this->selectedCategory = request()->query('category');
        }

        $this->updateCartCount();
    }

    public function handleCartUpdated($type = null)
    {
        $this->updateCartCount();

        if ($type === 'added') {
            $this->alertMessage = 'added';
        } elseif ($type === 'removed') {
            $this->alertMessage = 'removed';
        } else {
            $this->alertMessage = null;
        }

        $this->dispatchBrowserEvent('hide-success-message');
    }

    public function updateCartCount()
    {
        $this->cartCount = session()->has('cart')
            ? count(session('cart'))
            : 0;
    }

    public function filterByCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['images','variants','category']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $products = $query->paginate(9);

        return view('livewire.frontend.products', [
            'products' => $products
        ]);
    }
}
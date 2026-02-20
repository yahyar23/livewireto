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
    public $mensProducts = [];
    public $mensCategoryName = null; // أضفت هذا المتغير لضمان عدم حدوث خطأ في التعريف

    protected $listeners = ['cartUpdated' => 'handleCartUpdated'];

    public function mount()
    {
        // تحميل الأقسام
        $this->categories = Category::with('children.children')
            ->whereNull('parent_id')
            ->get();

        // استقبال slug القسم من الرابط
        if (request()->has('category')) {
            $this->selectedCategory = request()->query('category');
        }

        $this->updateCartCount();

        // 🔥 جلب 6 منتجات من القسم الذي يحمل slug معين (مثلاً القسم الثاني)
        // ملاحظة: قمت بالبحث بالـ ID هنا فقط لضمان جلب القسم المقصود برمجياً وتحويله لـ slug لاحقاً
        $mensCategory = Category::find(2);

        if ($mensCategory) {
            $this->mensCategoryName = $mensCategory->name;

            // جلب الـ IDs للقسم وأبنائه لضمان ظهور المنتجات
            $categoryIds = $this->getAllCategoryIds($mensCategory);

            $this->mensProducts = Product::with(['images','variants','category'])
                ->whereIn('category_id', $categoryIds)
                ->latest()
                ->take(6)
                ->get();
        }
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

    public function filterByCategory($categorySlug = null)
    {
        $this->selectedCategory = $categorySlug;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // دالة مساعدة لجلب كافة IDs الأقسام (الأب + الأبناء) لضمان ظهور المنتجات
    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }
        return $ids;
    }

    public function render()
    {
        $query = Product::with(['images','variants','category']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            // البحث عن القسم بواسطة الـ slug وجلب منتجاته مع منتجات أبنائه
            $category = Category::where('slug', $this->selectedCategory)->first();
            if ($category) {
                $categoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->paginate(9);

        return view('livewire.frontend.products', [
            'products' => $products
        ]);
    }
}
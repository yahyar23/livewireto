<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str; // استيراد كلاس Str

class Create extends Component
{
    public $name;
    public $description;
    public $parent_id;

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name', // يفضل أن يكون الاسم فريداً
        'parent_id' => 'nullable|exists:categories,id'
    ];

    public function save()
    {
        $this->validate();

        // إنشاء الـ Slug من الاسم
        // ملاحظة: Str::slug يدعم الكلمات الإنجليزية، للعربية سيعمل بشكل جيد في Laravel الحديث
        $slug = Str::slug($this->name);

        // للتأكد من عدم تكرار الـ Slug (اختياري ولكن مستحسن)
        $count = Category::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        Category::create([
            'name' => $this->name,
            'slug' => $slug, // إضافة الحقل هنا
            'description' => $this->description,
            'parent_id' => $this->parent_id
        ]);

        session()->flash('success', 'تم إنشاء القسم بنجاح ✅');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.categories.create', [
            'categories' => Category::whereNull('parent_id')
                ->with('children')
                ->get()
        ]);
    }
}
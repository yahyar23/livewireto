<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str; // استيراد كلاس Str

class Edit extends Component
{
    public $category;
    public $name;
    public $description;
    public $parent_id;

    protected function rules()
    {
        return [
            // أضفنا unique مع استثناء القسم الحالي لضمان عدم تكرار الاسم
            'name' => 'required|min:3|unique:categories,name,' . $this->category->id,
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
    }

    public function update()
    {
        $this->validate();

        // منع اختيار نفسه كأب
        if ($this->parent_id == $this->category->id) {
            session()->flash('error', 'لا يمكن اختيار القسم كأب لنفسه');
            return;
        }

        // منع اختيار أحد أبنائه كأب (منع الحلقة المغلقة)
        if ($this->isChildOf($this->parent_id)) {
            session()->flash('error', 'لا يمكن اختيار أحد الأبناء كقسم أب');
            return;
        }

        // إنشاء الـ Slug الجديد بناءً على الاسم المعدل
        $slug = Str::slug($this->name);

        // التحقق من عدم تكرار الـ Slug مع أقسام أخرى
        $count = Category::where('slug', $slug)->where('id', '!=', $this->category->id)->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($this->category->id);
        }

        $this->category->update([
            'name' => $this->name,
            'slug' => $slug, // تحديث الـ Slug هنا
            'description' => $this->description,
            'parent_id' => $this->parent_id
        ]);

        session()->flash('success', 'تم تحديث القسم بنجاح ✅');
    }

    // التحقق هل المختار أحد أبنائه
    private function isChildOf($parentId)
    {
        if (!$parentId) return false;

        $childrenIds = $this->getAllChildrenIds($this->category);

        return in_array($parentId, $childrenIds);
    }

    private function getAllChildrenIds($category)
    {
        $ids = [];

        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildrenIds($child));
        }

        return $ids;
    }

    public function render()
    {
        return view('livewire.categories.edit', [
            'categories' => Category::whereNull('parent_id')
                ->with('children')
                ->get()
        ]);
    }
}
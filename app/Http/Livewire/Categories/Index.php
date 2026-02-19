<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class Index extends Component
{
    public $name;
    public $description;
    public $parent_id;
    public $category_id;

    public $updateMode = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }

    public function render()
    {
        return view('livewire.categories.index', [
            // فقط الأقسام الرئيسية مع تحميل الأبناء بشكل متداخل
            'categories' => Category::whereNull('parent_id')
                ->with('children')
                ->get(),

            // جميع الأقسام لاستخدامها في dropdown التعديل
            'allCategories' => Category::all()
        ]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->category_id = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->category_id);

        // منع اختيار نفسه كأب
        if ($this->parent_id == $this->category_id) {
            session()->flash('error', 'لا يمكن اختيار القسم كأب لنفسه');
            return;
        }

        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id
        ]);

        session()->flash('success', 'تم التحديث بنجاح');

        $this->resetForm();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();

        session()->flash('success', 'تم الحذف بنجاح');
    }

    private function resetForm()
    {
        $this->name = null;
        $this->description = null;
        $this->parent_id = null;
        $this->category_id = null;
        $this->updateMode = false;
    }
}

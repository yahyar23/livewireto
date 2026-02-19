<?php

namespace App\Http\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;

class Create extends Component
{
    public $name;
    public $description;
    public $parent_id;

    protected $rules = [
        'name' => 'required|min:3',
        'parent_id' => 'nullable|exists:categories,id'
    ];

    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
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

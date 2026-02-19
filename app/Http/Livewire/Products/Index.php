<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
public $deleteId = null;

protected $listeners = ['confirmDelete'];

public function confirmDelete($id)
{
    $this->deleteId = $id;

    // إشعار JS لفتح المودال
    $this->dispatchBrowserEvent('openDeleteModal');
}

public function delete()
{
    if ($this->deleteId) {
        Product::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        $this->dispatchBrowserEvent('closeDeleteModal');
        session()->flash('success', 'تم حذف المنتج بنجاح');
    }
}


    public function render()
    {
        $products = Product::with(['category', 'images', 'variants'])
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.products.index', [
            'products' => $products
        ])->layout('layouts.app');
    }
}

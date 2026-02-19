<div style="margin-left:20px; border-left:1px solid #ccc; padding-left:10px;">

    {{ $category->name }}

    <button wire:click="edit({{ $category->id }})">✏️</button>
    <button wire:click="delete({{ $category->id }})">🗑️</button>

    @if($category->children->count())
        @foreach($category->children as $child)
            @include('livewire.categories.partials.category-item', ['category' => $child])
        @endforeach
    @endif

</div>

<tr>
    <td>{{ $category->id }}</td>

    <td>
        {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}
        @if($level > 0)
            └─
        @endif
        {{ $category->name }}
    </td>

    <td>{{ $category->description }}</td>

    <td>
        @if($level == 0)
            رئيسي
        @else
            فرعي مستوى {{ $level }}
        @endif
    </td>

    <td>
    <!-- زر تعديل يذهب لصفحة Edit مستقلة -->
    <a href="{{ route('admin.categories.edit', $category->id) }}" 
       class="btn btn-sm btn-warning">
       ✏ تعديل
    </a>

    <!-- زر حذف مباشر -->
    <button wire:click="delete({{ $category->id }})" 
            class="btn btn-sm btn-danger"
            onclick="return confirm('هل أنت متأكد من الحذف؟')">
        🗑️ حذف
    </button>
</td>

</tr>

@if($category->children->count())
    @foreach($category->children as $child)
        @include('livewire.categories.partials.row', [
            'category' => $child,
            'level' => $level + 1
        ])
    @endforeach
@endif

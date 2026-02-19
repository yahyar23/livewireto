<div>

    <h2>إدارة الأقسام</h2>

    <a href="{{ route('admin.categories.create') }}" 
       style="background:#28a745;color:white;padding:6px 12px;text-decoration:none;border-radius:4px;">
        + إضافة قسم
    </a>

    <br><br>

    <table border="1" width="100%" cellpadding="8" cellspacing="0">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>#</th>
                <th>اسم القسم</th>
                <th>الوصف</th>
                <th>المستوى</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                @include('livewire.categories.partials.row', [
                    'category' => $category,
                    'level' => 0
                ])
            @endforeach
        </tbody>
    </table>
    @if($updateMode)

    <hr>

    <h3>تعديل القسم</h3>

    @if (session()->has('error'))
        <div style="color:red;">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div style="color:green;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-top:10px;">

        <input type="text" wire:model="name" placeholder="اسم القسم">

        <textarea wire:model="description" placeholder="الوصف"></textarea>

        <select wire:model="parent_id">
            <option value="">قسم رئيسي</option>

            @foreach($allCategories as $cat)
                @if($cat->id != $category_id)
                    <option value="{{ $cat->id }}">
                        {{ $cat->name }}
                    </option>
                @endif
            @endforeach
        </select>

        <button wire:click="update">تحديث</button>

        <button wire:click="$set('updateMode', false)">
            إلغاء
        </button>

    </div>

@endif

     <a href="{{ route('admin.dashboard') }}" 
                       class="btn btn-secondary">
                       رجوع للصفحة الرئيسية
                    </a>
</div>

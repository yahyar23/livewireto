<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            تعديل القسم
        </div>

        <div class="card-body">

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="update">

                <div class="mb-3">
                    <label class="form-label">اسم القسم</label>
                    <input type="text" class="form-control" wire:model="name">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea class="form-control" wire:model="description"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">القسم الأب</label>
                    <select class="form-select" wire:model="parent_id">
                        <option value="">قسم رئيسي</option>

                        @foreach($categories as $categoryItem)
                            @include('livewire.categories.partials.select-option', [
                                'category' => $categoryItem,
                                'level' => 0
                            ])
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="btn btn-secondary">
                        رجوع
                    </a>
     <a href="{{ route('admin.dashboard') }}" 
                       class="btn btn-secondary">
                       رجوع للصفحة الرئيسية
                    </a>
                    <button type="submit" class="btn btn-warning">
                        تحديث القسم
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

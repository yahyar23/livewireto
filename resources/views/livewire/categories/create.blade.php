<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            إضافة قسم جديد
        </div>

        <div class="card-body">

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="save">

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

                        @foreach($categories as $category)
                            @include('livewire.categories.partials.select-option', [
                                'category' => $category,
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
                       رجوع لقاعدة التحكم
                    </a>

                    <button type="submit" class="btn btn-success">
                        حفظ القسم
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

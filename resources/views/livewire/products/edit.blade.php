<div class="container mt-4" dir="rtl">

    <h2 class="mb-4">تعديل المنتج</h2>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label class="form-label">اسم المنتج</label>
            <input type="text" class="form-control" wire:model="name">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">القسم</label>
            <select class="form-select" wire:model="category_id">
                <option value="">اختر القسم</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                        @if($category->children)
                            @foreach($category->children as $child)
                                &nbsp;&nbsp;— {{ $child->name }}
                            @endforeach
                        @endif
                    </option>
                @endforeach
            </select>
            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">وصف المنتج</label>
            <textarea class="form-control" wire:model="description" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">صور المنتج (يمكن رفع عدة صور)</label>
            <input type="file" class="form-control" wire:model="images" multiple>
            @if($images)
                <div class="mt-2">
                    @foreach($images as $image)
                        <img src="{{ $image->temporaryUrl() }}" width="80" class="me-2 mb-2">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" wire:model="has_variants" id="hasVariants">
            <label class="form-check-label" for="hasVariants">المنتج يحتوي على Variants</label>
        </div>

        @if(!$has_variants)
            <div class="mb-3">
                <label class="form-label">السعر</label>
                <input type="number" class="form-control" wire:model="price">
            </div>
            <div class="mb-3">
                <label class="form-label">المخزون</label>
                <input type="number" class="form-control" wire:model="stock">
            </div>
        @else
            <h5>Variants</h5>
            @foreach($variants as $index => $variant)
                <div class="row mb-2 align-items-center">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="SKU" wire:model="variants.{{ $index }}.sku">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" placeholder="السعر" wire:model="variants.{{ $index }}.price">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" placeholder="المخزون" wire:model="variants.{{ $index }}.stock">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger" wire:click="removeVariant({{ $index }})">حذف</button>
                    </div>
                </div>
            @endforeach
            <button type="button" class="btn btn-primary mb-3" wire:click="addVariant">إضافة Variant جديد</button>
        @endif

        <div class="mb-3">
            <button type="submit" class="btn btn-success">حفظ التعديلات</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </form>
</div>

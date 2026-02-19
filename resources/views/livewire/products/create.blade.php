<div class="container mt-5 mb-5" dir="rtl">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">إضافة منتج جديد</h4>
        </div>
        <div class="card-body">

            {{-- Flash Message --}}
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="save">

                {{-- اسم المنتج --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">اسم المنتج</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        wire:model="name" placeholder="ادخل اسم المنتج">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- القسم --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">القسم</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                        wire:model="category_id">
                        <option value="">اختر القسم</option>
                        @foreach($categories as $category)
                            {{-- عرض الأب --}}
                            <option value="{{ $category->id }}" class="fw-bold">
                                {{ $category->name }}
                            </option>

                            {{-- عرض الأبناء --}}
                            @if ($category->children)
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}">
                                        &nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- الوصف --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">الوصف</label>
                    <textarea class="form-control" wire:model="description"
                        placeholder="ادخل وصف المنتج"></textarea>
                </div>

                {{-- الصور --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">صور المنتج</label>
                    <input type="file" class="form-control" wire:model="images" multiple>
                    @if ($images)
                        <div class="mt-2">
                            <small>معاينة الصور:</small>
                            <div class="d-flex flex-wrap mt-1">
                                @foreach($images as $image)
                                    <img src="{{ $image->temporaryUrl() }}" class="me-2 mb-2 rounded" width="80" height="80">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- التحقق من وجود Variants --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" wire:model="has_variants" id="hasVariants">
                    <label class="form-check-label fw-bold" for="hasVariants">
                        يحتوي على خيارات متعددة (Variants)
                    </label>
                </div>

                {{-- السعر والكمية --}}
                @if (!$has_variants)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">السعر</label>
                            <input type="number" class="form-control" wire:model="price" placeholder="ادخل السعر">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الكمية</label>
                            <input type="number" class="form-control" wire:model="stock" placeholder="ادخل الكمية">
                        </div>
                    </div>
                @endif

                {{-- Variants --}}
                @if ($has_variants)
                    <div class="mb-3">
                        <h5 class="fw-bold">الخيارات المتعددة (Variants)</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variants as $index => $variant)
                                    <tr>
                                        <td><input type="text" class="form-control" wire:model="variants.{{ $index }}.sku"></td>
                                        <td><input type="number" class="form-control" wire:model="variants.{{ $index }}.price"></td>
                                        <td><input type="number" class="form-control" wire:model="variants.{{ $index }}.stock"></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                wire:click="removeVariant({{ $index }})">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success btn-sm" wire:click="addVariant">إضافة خيار جديد</button>
                    </div>
                @endif

                {{-- زر الحفظ --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        حفظ المنتج
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

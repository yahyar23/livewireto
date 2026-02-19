<div class="container py-4" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>المنتجات</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            ➕ إضافة منتج جديد
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <input type="text" wire:model.debounce.500ms="search" class="form-control text-end" placeholder="بحث باسم المنتج">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-end">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>القسم</th>
                    <th>الوصف</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الـ Variants</th>
                    <th>صورة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ Str::limit($product->description, 50) }}</td>
                        <td>
                            @if(!$product->has_variants)
                                {{ $product->variants->first()->price ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(!$product->has_variants)
                                {{ $product->variants->first()->stock ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($product->has_variants)
                                <ul class="list-unstyled mb-0">
                                    @foreach($product->variants as $variant)
                                        <li>
                                            <strong>{{ $variant->sku }}</strong> : {{ $variant->price }} | {{ $variant->stock }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($product->images->count())
                                @foreach($product->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" width="50" height="50" class="rounded me-1 mb-1">
                                @endforeach
                            @else
                                <span class="text-muted">لا توجد صورة</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning mb-1">
                                ✏️
                            </a>
                           <!-- زر الحذف داخل الجدول -->
<button class="btn btn-danger btn-sm" wire:click.prevent="$emit('confirmDelete', {{ $product->id }})">
    🗑️ حذف
</button>

<!-- Modal Bootstrap -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body">
        هل أنت متأكد من حذف هذا المنتج؟ لن تتمكن من التراجع عن هذا الإجراء.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
      </div>
    </div>
  </div>
</div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">لا توجد منتجات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
             <a href="{{ route('admin.dashboard') }}" 
                       class="btn btn-secondary">
                       رجوع للصفحة الرئيسية
                    </a>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    window.addEventListener('openDeleteModal', event => {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();

        document.getElementById('confirmDeleteBtn').onclick = function () {
            @this.delete(); // استدعاء دالة Livewire
        };
    });

    window.addEventListener('closeDeleteModal', event => {
        var deleteModalEl = document.getElementById('deleteModal');
        var modal = bootstrap.Modal.getInstance(deleteModalEl);
        modal.hide();
    });

});
</script>

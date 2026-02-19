

<div class="container py-4" dir="rtl">

    <h2 class="mb-4 text-end">الطلبات</h2>

    <div class="table-responsive">
        <table class="table table-bordered text-end align-middle">
            <thead class="table-dark">
                     
                        <h2 class="mb-4 text-end">تفاصيل الزبون وتفاصيل المنتجات التي تم طلبها</h2>

                <tr>
                    <th>#</th>
                    <th>اسم الزبون</th>
                    <th>الهاتف</th>
                    <th>العنوان</th>
                    <th>المجموع</th>
                    <th>طريقة الدفع</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <a  href="{{ route('admin.dashboard') }}" 
                       class="btn btn-primary py-2">
                       رجوع لقاعدة التحكم
                    </a>
            <tbody>
                @forelse($orders as $order)
                    <!-- صف الطلب -->
                    <tr class="table-primary">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->visitor_name ?? $order->user->name ?? '-' }}</td>
                        <td>{{ $order->visitor_phone ?? $order->user->phone ?? '-' }}</td>
                        <td>{{ $order->address }}</td>
                        <td>{{ $order->total }} $</td>
                        <td>{{ $order->payment_method ?? '-' }}</td>
                        <td>
                            <span class="badge @if($order->status == 'pending') bg-warning @else bg-success @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>
                            @if($order->status != 'completed')
                                <button class="btn btn-danger btn-sm" wire:click="markCompleted({{ $order->id }})">
                                    ✔️ إنهاء الطلب
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <!-- صفوف المنتجات داخل الطلب -->
                    @foreach($order->items as $item)
                        <tr class="table-light">
                            <td colspan="2" class="text-end ps-5">المنتج: {{ $item->product_name }}</td>
                            <td>{{ $item->quantity }} × {{ $item->price }} $</td>
                            <td colspan="5"></td>
                        </tr>
                    @endforeach

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">لا توجد طلبات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal تأكيد إنهاء الطلب -->
    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">تأكيد إنهاء الطلب</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            هل أنت متأكد من تحديث حالة هذا الطلب إلى "مكتمل"؟
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="button" class="btn btn-danger" wire:click="completeOrder">نعم، إنهاء</button>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    window.addEventListener('openCompleteModal', () => {

        let modalEl = document.getElementById('completeOrderModal');

        let modal = new bootstrap.Modal(modalEl);

        modal.show();

        // إزالة أي focus متبقٍ
        setTimeout(() => {
            document.activeElement.blur();
        }, 100);
    });

    window.addEventListener('closeCompleteModal', () => {

        let modalEl = document.getElementById('completeOrderModal');
        let modal = bootstrap.Modal.getInstance(modalEl);

        if (modal) {
            modal.hide();
        }

        // تنظيف الخلفية في حال علقت
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    });

    window.addEventListener('notify', event => {

        const alertPlaceholder = document.createElement('div');

        alertPlaceholder.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" style="z-index:2000;">
                ${event.detail.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(alertPlaceholder);

        setTimeout(() => {
            alertPlaceholder.remove();
        }, 4000);
    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

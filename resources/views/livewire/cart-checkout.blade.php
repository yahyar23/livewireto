<div class="container py-5" dir="rtl">
    <h2 class="mb-4 text-end">السلة و إتمام الطلب</h2>

    <!-- Alert نجاح الطلب -->
    <div id="orderSuccessAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display:none;">
        <strong>✅ تم إتمام الطلب بنجاح!</strong> شكرًا لك على التسوق معنا.
        <button type="button" class="btn-close" aria-label="إغلاق"
                onclick="document.getElementById('orderSuccessAlert').style.display='none'"></button>
    </div>

    @if(count($cart) > 0)
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-end align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                        <th>إزالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $cartKey => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item['name'] }}
                                @if(!empty($item['variant']))
                                    <br>
                                    <small class="text-muted">
                                        ({{ $item['variant'] }})
                                    </small>
                                @endif
                            </td>

                            <td>{{ $item['price'] }} $</td>

                            <td>
                                <input type="number"
                                       min="1"
                                       class="form-control"
                                       style="width:80px"
                                       wire:change="updateQuantity('{{ $cartKey }}', $event.target.value)"
                                       value="{{ $item['quantity'] }}">
                            </td>

                            <td>
                                {{ $item['price'] * $item['quantity'] }} $
                            </td>

                            <td>
                                <button class="btn btn-danger btn-sm"
                                        wire:click="remove('{{ $cartKey }}')">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4" class="text-end fw-bold">المجموع الكلي</td>
                        <td colspan="2" class="fw-bold">{{ $total }} $</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- بيانات الزائر إذا لم يكن مسجل -->
        @if(!auth()->check())
            <div class="mb-3">
                <input type="text"
                       wire:model.defer="name"
                       class="form-control mb-2"
                       placeholder="الاسم الكامل">

                <input type="text"
                       wire:model.defer="phone"
                       class="form-control mb-2"
                       placeholder="رقم الهاتف">

                <textarea wire:model.defer="address"
                          class="form-control"
                          placeholder="العنوان الكامل"></textarea>
            </div>
        @else
            <div class="mb-3">
                <textarea wire:model.defer="address"
                          class="form-control"
                          placeholder="العنوان الكامل"></textarea>
            </div>
        @endif

        <div class="text-end">
            <button class="btn btn-success"
                    wire:click="placeOrder">
                إتمام الطلب
            </button>
        </div>

    @else
        <p class="text-center text-muted">السلة فارغة</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('notify', event => {

            const alertEl = document.getElementById('orderSuccessAlert');
            alertEl.style.display = 'block';
            alertEl.classList.add('show');

            setTimeout(() => {
                alertEl.classList.remove('show');
                alertEl.style.display = 'none';
            }, 4000);
        });
    });
</script>
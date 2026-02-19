<div class="position-fixed top-0 end-0 h-100 bg-white shadow p-3"
     style="width: 350px; transform: translateX({{ $showCart ? '0' : '100%' }}); transition: transform 0.3s; z-index: 1050;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">سلة التسوق 🛒</h5>

        <!-- زر إغلاق السلة -->
        <button class="btn btn-sm btn-outline-secondary"
                wire:click="$set('showCart', false)">
            ×
        </button>
    </div>

    {{-- رسالة العملية --}}
    @if($message)
        <div class="alert alert-{{ $messageType }} alert-dismissible fade show"
             role="alert"
             x-data
             x-init="setTimeout(() => $wire.set('message',''), 2000)">
             
            {{ $message }}

            <button type="button"
                    class="btn-close"
                    wire:click="$set('message','')">
            </button>
        </div>
    @endif


    @if(count($cart) > 0)

        <ul class="list-group mb-3">
            @foreach($cart as $id => $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center gap-2">
                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}"
                                 width="50"
                                 style="object-fit:cover"
                                 class="rounded">
                        @endif

                        <div>
                            <div>{{ $item['name'] }}</div>
                            <small>
                                {{ $item['price'] }} $
                                ×
                                {{ $item['quantity'] }}
                            </small>
                        </div>
                    </div>

                    <!-- زر حذف -->
                    <button class="btn btn-sm btn-danger"
                            wire:click="removeFromCart({{ $id }})">
                        &times;
                    </button>

                </li>
            @endforeach
        </ul>

        <a href="{{ route('checkout') }}"
           class="btn btn-success w-100">
            إكمال الطلب
        </a>

    @else
        <p class="text-muted text-center mt-3">السلة فارغة</p>
    @endif

</div>

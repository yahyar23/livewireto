<div dir="rtl">


    <!-- ✅ رسالة النجاح -->
@if($alertMessage)
    <div id="successAlert"
         class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow"
         style="z-index: 2000; min-width:300px;">

        @if($alertMessage === 'added')
            ✅ تم إضافة المنتج إلى السلة بنجاح
        @elseif($alertMessage === 'removed')
            🗑️ تم حذف المنتج من السلة
        @endif

        <button type="button"
                class="btn-close"
                wire:click="$set('alertMessage', null)">
        </button>
    </div>
@endif


    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">

            <a href="{{ route('home') }}" class="navbar-brand fw-bold">
                🏠 الرئيسية
            </a>

            <div class="d-flex align-items-center gap-2">

                <button class="btn btn-primary btn-sm"
                        data-bs-toggle="collapse"
                        data-bs-target="#categoriesBar">
                    📂 الأقسام
                </button>

                <button class="btn btn-outline-dark position-relative btn-sm"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#cartOffcanvas">

                    🛒 السلة

                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                    @endif
                </button>

            </div>
        </div>
    </nav>


    <div class="container py-4">

        <h2 class="mb-4 text-end">منتجاتنا</h2>

        <!-- ================= الأقسام ================= -->
        <div id="categoriesBar" class="collapse mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">

                        <button class="btn btn-outline-dark btn-sm"
                                wire:click="filterByCategory(null)">
                            الكل
                        </button>

                        @foreach($categories as $category)

                            <button class="btn btn-outline-primary btn-sm"
                                    wire:click="filterByCategory({{ $category->id }})">
                                {{ $category->name }}
                            </button>

                            @foreach($category->children as $child)

                                <button class="btn btn-outline-secondary btn-sm"
                                        wire:click="filterByCategory({{ $child->id }})">
                                    — {{ $child->name }}
                                </button>

                                @foreach($child->children as $subChild)

                                    <button class="btn btn-outline-success btn-sm"
                                            wire:click="filterByCategory({{ $subChild->id }})">
                                        —— {{ $subChild->name }}
                                    </button>

                                @endforeach
                            @endforeach
                        @endforeach

                    </div>
                </div>
            </div>
        </div>


        <!-- ================= البحث ================= -->
        <div class="mb-4 text-end">
            <input type="text"
                   wire:model.debounce.500ms="search"
                   class="form-control text-end"
                   placeholder="بحث باسم المنتج">
        </div>


        <!-- ================= المنتجات ================= -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm position-relative">

                        @if($product->images->count())
                            <div id="carouselProduct{{ $product->id }}"
                                 class="carousel slide"
                                 data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($product->images as $index => $image)
                                        <div class="carousel-item @if($index==0) active @endif">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                 class="d-block w-100"
                                                 style="height:200px;object-fit:cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('images/no-image.png') }}"
                                 class="card-img-top"
                                 style="height:200px;object-fit:cover;">
                        @endif

                        <div class="card-body text-end">
                            <h5>{{ $product->name }}</h5>
                            <p class="text-muted mb-1">
                                {{ $product->category->name ?? '-' }}
                            </p>

                            <p>{{ Str::limit($product->description, 80) }}</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold">
                                    @if(!$product->has_variants)
                                        {{ $product->variants->first()->price ?? '-' }} $
                                    @else
                                        -
                                    @endif
                                </span>

                                <button class="btn btn-outline-primary btn-sm"
                                     wire:click="$emit('addToCart', {{ $product->id }}, {{ $product->variants->first()->id }})">
                                        
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">لا توجد منتجات</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 text-center">
            {{ $products->links() }}
        </div>

    </div>


    <!-- ================= OFFCANVAS السلة ================= -->
    <div wire:ignore.self
         class="offcanvas offcanvas-end"
         tabindex="-1"
         id="cartOffcanvas">

        <div class="offcanvas-header">
            <h5>🛒 سلة المشتريات</h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas">
            </button>
        </div>

        <div class="offcanvas-body">
            <livewire:cart />
        </div>
    </div>

</div>


<script>
    window.addEventListener('hide-success-message', function () {
        setTimeout(function () {
            let alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000);
    });
</script>



<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

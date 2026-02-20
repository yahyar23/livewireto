<div class="container py-4" dir="rtl">
    <h2>{{ $product->name }}</h2>
<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container d-flex justify-content-between">

        <a href="{{ route('frontend.products') }}" class="navbar-brand fw-bold">
            🏠 الرئيسية
        </a>

        <button class="btn btn-outline-dark position-relative"
                data-bs-toggle="offcanvas"
                data-bs-target="#cartOffcanvas">

            🛒 السلة

            @if($cartCount > 0)
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </button>
        <div class="mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">

                <button class="btn btn-outline-dark btn-sm"
                        wire:click="goToCategory(null)">
                    الكل
                </button>

                @foreach($categories as $category)

                    <button class="btn btn-outline-primary btn-sm"
                            wire:click="goToCategory({{ $category->id }})">
                        {{ $category->name }}
                    </button>

                    @foreach($category->children as $child)

                        <button class="btn btn-outline-secondary btn-sm"
                                wire:click="goToCategory({{ $child->id }})">
                            — {{ $child->name }}
                        </button>

                        @foreach($child->children as $subChild)

                            <button class="btn btn-outline-success btn-sm"
                                    wire:click="goToCategory({{ $subChild->id }})">
                                —— {{ $subChild->name }}
                            </button>

                        @endforeach
                    @endforeach

                @endforeach

            </div>
        </div>
    </div>
</div>

    </div>
</nav>
    <!-- الصور -->
    @if($product->images->count())
        <div id="carouselProduct{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($product->images as $index => $image)
                    <div class="carousel-item @if($index==0) active @endif">
                        <img src="{{ asset('storage/'.$image->image_path) }}" class="d-block w-100" style="height:300px; object-fit:cover;">
                    </div>
                @endforeach
            </div>
            @if($product->images->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @endif

    <p>{{ $product->description }}</p>

    @if($product->variants->count())
        <p>السعر: <strong>{{ $product->variants->first()->price ?? '-' }} $</strong></p>

        <label>اختر المقاس أو النوع:</label>
        <select wire:model="selectedVariantId" class="form-select w-auto mb-3">
            @foreach($product->variants as $variant)
                <option value="{{ $variant->id }}">
                    {{ $variant->name }} (المتوفر: {{ $variant->stock }})
                    {{ $variant->name }} (المقاسات: {{ $variant->sku }})
                </option>
            @endforeach
        </select>
    @else
        <p>السعر: <strong>{{ $product->variants->first()->price ?? '-' }} $</strong></p>
    @endif

    <button class="btn btn-primary"
            wire:click="addToCart">
        أضف إلى السلة
    </button>

    <!-- OFFCANVAS السلة -->
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

<!-- إشعارات نجاح الإضافة -->
<script>
    window.addEventListener('showSuccessMessage', event => {
        const alertPlaceholder = document.createElement('div');
        alertPlaceholder.innerHTML = `<div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" role="alert" style="z-index:2000; min-width:300px;">
            ✅ ${event.detail.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        document.body.prepend(alertPlaceholder);
        setTimeout(()=>{ alertPlaceholder.remove(); }, 4000);
    });
</script>






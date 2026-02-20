<div class="bg-light-custom" dir="rtl">
    <style>
        :root {
            --main-red: #e62e2e;
            --soft-gray: #f8f9fa;
            --border-radius: 12px;
        }
        body { background-color: #fcfcfc; font-family: 'Cairo', sans-serif; }
        .product-container { background: white; border-radius: var(--border-radius); padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* قسم الصور */
        .thumb-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 1px solid #eee; margin-bottom: 10px; transition: 0.3s; }
        .thumb-img:hover, .thumb-img.active { border-color: var(--main-red); box-shadow: 0 0 5px rgba(230, 46, 46, 0.3); }
        .main-product-img { width: 100%; border-radius: var(--border-radius); background: #f9f9f9; padding: 20px; }

        /* معلومات المنتج */
        .product-title { font-weight: 800; font-size: 2rem; color: #333; }
        .price-tag { color: var(--main-red); font-size: 2.5rem; font-weight: 800; }
        .old-price { text-decoration: line-through; color: #999; font-size: 1.2rem; }
        
        /* المقاسات */
        .size-badge { cursor: pointer; border: 1px solid #ddd; padding: 8px 15px; border-radius: 8px; transition: 0.3s; background: white; }
        .size-badge.active { background: var(--main-red); color: white; border-color: var(--main-red); }

        /* الأزرار */
        .btn-add-to-cart { background: var(--main-red); color: white; border: none; padding: 15px 40px; border-radius: 10px; font-weight: bold; font-size: 1.2rem; transition: 0.3s; width: 100%; }
        .btn-add-to-cart:hover { background: #c02626; transform: translateY(-2px); }
        .btn-wishlist { border: 1px solid #ddd; background: white; padding: 15px; border-radius: 10px; transition: 0.3s; }

        /* --- نظام السلة الخاص --- */
        #customCartDrawer {
            position: fixed;
            top: 0;
            left: -400px;
            width: 380px;
            max-width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            transition: all 0.3s ease-in-out;
            box-shadow: 5px 0 25px rgba(0,0,0,0.15);
            visibility: hidden;
        }
        #customCartDrawer.active {
            left: 0;
            visibility: visible;
        }
        #cartOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            display: none;
            backdrop-filter: blur(2px);
        }
        #cartOverlay.active {
            display: block;
        }

        .promo-bar { background: white; padding: 20px; border-radius: 10px; margin-top: 40px; border: 1px solid #eee; }
    </style>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 sticky-top">
        <div class="container d-flex justify-content-between">
            <a href="{{ route('frontend.products') }}" class="navbar-brand fw-bold text-danger fs-3">التاجر</a>
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn position-relative" onclick="toggleCart(true)">
                    <i class="bi bi-cart3 fs-4"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-0 badge rounded-pill bg-danger">{{ $cartCount }}</span>
                    @endif
                </button>
                <a href="{{ route('frontend.products') }}" class="text-dark text-decoration-none fw-bold">الرئيسية</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.products') }}">الرئيسية</a></li>
                <li class="breadcrumb-item">
                    <a href="#" wire:click.prevent="goToCategory('{{ $product->category->slug ?? '' }}')">
                        {{ $product->category->name ?? 'قسم' }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="product-container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="row flex-row-reverse">
                        <div class="col-10">
                            @if($product->images->count())
                                <div class="main-product-img text-center">
                                    <img src="{{ asset('storage/'.$product->images->first()->image_path) }}" class="img-fluid" id="mainImg">
                                </div>
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="main-product-img">
                            @endif
                        </div>
                        <div class="col-2 d-flex flex-column gap-2">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/'.$img->image_path) }}" class="thumb-img" onclick="document.getElementById('mainImg').src=this.src">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h1 class="product-title mb-2">{{ $product->name }}</h1>
                    
                    <div class="d-flex align-items-center gap-2 mb-3 text-warning">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        <span class="text-muted small">(124 تقييم)</span>
                    </div>

                    <div class="mb-4">
                        <span class="price-tag">{{ $product->variants->first()->price ?? '0' }} <small>$</small></span>
                    </div>

                    <div class="mb-4 text-muted">
                        <p class="fw-bold text-dark mb-1">الوصف:</p>
                        <p>{{ $product->description }}</p>
                    </div>

                    @if($product->variants->count())
                        <div class="mb-4">
                            <p class="fw-bold mb-2">الخيارات المتاحة:</p>
                            <select wire:model="selectedVariantId" class="form-select w-auto mb-3">
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->name }} - (المتوفر: {{ $variant->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <button class="btn btn-add-to-cart" wire:click="addToCart">
                                <i class="bi bi-cart-plus me-2"></i> أضف إلى السلة
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-wishlist w-100">
                                <i class="bi bi-heart fs-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5 mb-4 fw-bold border-bottom pb-2">📂 استكشف الأقسام</h3>
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <button class="btn btn-outline-primary btn-sm fw-bold" 
                                wire:click="goToCategory('{{ $category->slug }}')">
                            {{ $category->name }}
                        </button>

                        @foreach($category->children as $child)
                            <button class="btn btn-outline-secondary btn-sm" 
                                    wire:click="goToCategory('{{ $child->slug }}')">
                                — {{ $child->name }}
                            </button>

                            @foreach($child->children as $subChild)
                                <button class="btn btn-outline-success btn-sm" 
                                        wire:click="goToCategory('{{ $subChild->slug }}')">
                                    —— {{ $subChild->name }}
                                </button>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mt-5 text-center g-3 promo-bar">
            <div class="col-md-3 col-6">
                <i class="bi bi-shield-check text-danger fs-3"></i>
                <p class="mb-0 fw-bold">دفع آمن</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-truck text-danger fs-3"></i>
                <p class="mb-0 fw-bold">شحن سريع</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-arrow-left-right text-danger fs-3"></i>
                <p class="mb-0 fw-bold">إرجاع مجاني</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-headset text-danger fs-3"></i>
                <p class="mb-0 fw-bold">دعم 24/7</p>
            </div>
        </div>
    </div>

    <div id="cartOverlay" onclick="toggleCart(false)"></div>
    <div id="customCartDrawer">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <h5 class="fw-bold mb-0">🛒 سلة المشتريات</h5>
            <button type="button" class="btn-close" onclick="toggleCart(false)"></button>
        </div>
        <div class="p-3 h-100 overflow-auto">
            <livewire:cart />
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
    function toggleCart(isOpen) {
        const drawer = document.getElementById('customCartDrawer');
        const overlay = document.getElementById('cartOverlay');
        
        if (isOpen) {
            drawer.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            drawer.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    window.addEventListener('showSuccessMessage', event => {
        toggleCart(true);
    });
</script>
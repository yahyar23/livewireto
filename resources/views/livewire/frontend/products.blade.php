<div dir="rtl" class="bg-light-custom">
    <style>
        :root {
            --main-red: #e62e2e;
            --soft-gray: #f8f9fa;
            --border-radius: 15px;
        }
        body { background-color: #f4f7f6; font-family: 'Cairo', sans-serif; }
        .bg-light-custom { background-color: #f4f7f6; min-height: 100vh; }
        
        /* Navbar Custom */
        .custom-nav { background: #fff; border-bottom: 2px solid var(--main-red); }
        .search-input { border-radius: 20px; border: 1px solid #ddd; padding: 5px 20px; }

        /* Banner Section */
        .hero-banner {
            background: linear-gradient(45deg, #ff7675, #d63031);
            border-radius: var(--border-radius);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        /* Categories Icons */
        .cat-item {
            background: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
            border: 1px solid transparent;
            min-width: 100px;
        }
        .cat-item:hover { border-color: var(--main-red); transform: translateY(-5px); }
        .cat-item img { width: 50px; height: 50px; margin-bottom: 8px; }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: var(--border-radius);
            transition: 0.3s;
            overflow: hidden;
            background: #fff;
        }
        .product-card:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .product-image-wrapper {
            background: #f9f9f9;
            padding: 20px;
            position: relative;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--main-red);
            color: white;
            padding: 2px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-add-cart {
            background-color: var(--main-red);
            color: white;
            border-radius: 8px;
            width: 100%;
            border: none;
            padding: 8px;
            transition: 0.3s;
        }
        .btn-add-cart:hover { background-color: #c02626; color: white; }
        
        /* Footer Promo */
        .promo-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 40px;
            border: 1px solid #eee;
        }
    </style>

    @if($alertMessage)
    <div id="successAlert" class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" style="z-index: 2000; min-width:300px;">
        {!! $alertMessage === 'added' ? '✅ تم إضافة المنتج بنجاح' : '🗑️ تم حذف المنتج من السلة' !!}
        <button type="button" class="btn-close" wire:click="$set('alertMessage', null)"></button>
    </div>
    @endif

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-danger fs-4 fw-bold text-decoration-none">التاجر</a>
                 <button class="btn btn-primary btn-sm"
                    data-bs-toggle="collapse"
                    data-bs-target="#categoriesBar">
                📂 الأقسام
            </button>
            
                <div class="input-group d-none d-md-flex">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control search-input" placeholder="ابحث عن المنتجات...">
                </div>
            </div>
            

            <div class="d-flex align-items-center gap-3">
                <button class="btn position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class="bi bi-cart3 fs-4"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-0 badge rounded-pill bg-danger">{{ $cartCount }} </span>
                    @endif
                </button>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        
        <div class="hero-banner d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">تخفيضات كبرى</h1>
                <h2 class="display-4 fw-bold">حتى 50%</h2>
                <button class="btn btn-dark rounded-pill px-4 mt-3">تسوق الآن</button>
            </div>
            <div class="d-none d-md-block">
                <i class="bi bi-gift-fill" style="font-size: 100px; opacity: 0.3;"></i>
            </div>
        </div>

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
                                    wire:click="filterByCategory('{{ $category->slug }}')">
                                {{ $category->name }}
                            </button>

                            @foreach($category->children as $child)
                                <button class="btn btn-outline-secondary btn-sm"
                                        wire:click="filterByCategory('{{ $child->slug }}')">
                                    — {{ $child->name }}
                                </button>

                                @foreach($child->children as $subChild)
                                    <button class="btn btn-outline-success btn-sm"
                                            wire:click="filterByCategory('{{ $subChild->slug }}')">
                                        —— {{ $subChild->name }}
                                    </button>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <h4 class="mb-4 fw-bold border-bottom pb-2">منتجات جديدة</h4>

        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100 shadow-sm">
                        
                        <div class="product-image-wrapper">
                            <span class="discount-badge">30% OFF</span>
                            @if($product->images->count())
                                <a href="{{ route('product.details', $product->slug) }}"> 
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                                </a>    
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded">
                            @endif
                        </div>

                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">{{ $product->category->name ?? 'عام' }}</p>
                            
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                <span class="text-danger fw-bold fs-5">
                                    {{ $product->variants->first()->price ?? '0' }} $
                                </span>
                            </div>

                            <button class="btn btn-add-cart d-flex align-items-center justify-content-center gap-2"
                                wire:click="$emit('addToCart', {{ $product->id }}, {{ $product->variants->first()->id }})">
                                <i class="bi bi-plus-lg"></i>
                                أضف إلى السلة
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <p class="mt-3">لا توجد منتجات حالياً</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>


        @if($mensCategoryName)
            <h4 class="mt-5 mb-4 fw-bold border-bottom pb-2">
                {{ $mensCategoryName }}
            </h4>
        @endif

        <div class="row g-4">
            @forelse($mensProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100 shadow-sm">

                        <div class="product-image-wrapper">
                            <span class="discount-badge">30% OFF</span>

                            @if($product->images->count())
                                <a href="{{ route('product.details', $product->slug) }}">
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                         class="img-fluid rounded"
                                         alt="{{ $product->name }}">
                                </a>
                            @else
                                <img src="{{ asset('images/no-image.png') }}"
                                     class="img-fluid rounded">
                            @endif
                        </div>

                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">
                                {{ $product->category->name ?? 'عام' }}
                            </p>

                            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                <span class="text-danger fw-bold fs-5">
                                    {{ $product->variants->first()->price ?? '0' }} $
                                </span>
                            </div>

                            <button class="btn btn-add-cart d-flex align-items-center justify-content-center gap-2"
                                wire:click="$emit('addToCart', {{ $product->id }}, {{ $product->variants->first()->id }})">
                                <i class="bi bi-plus-lg"></i>
                                أضف إلى السلة
                            </button>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam display-5 text-muted"></i>
                    <p class="mt-3">لا توجد منتجات لهذا القسم</p>
                </div>
            @endforelse
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
            if (alert) alert.style.display = 'none';
        }, 3000);
    });
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
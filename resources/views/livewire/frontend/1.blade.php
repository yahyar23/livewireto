<div dir="rtl">

    <!-- ✅ Navbar علوي جميل -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">

            <a href="{{ route('home') }}" class="navbar-brand fw-bold">
                🏠 الرئيسية
            </a>

            <button class="btn btn-primary btn-sm"
                    data-bs-toggle="collapse"
                    data-bs-target="#categoriesBar">
                📂 الأقسام
            </button>

        </div>
    </nav>


    <div class="container py-4">

        <h2 class="mb-4 text-end">منتجاتنا</h2>

        <!-- ✅ عرض الأقسام بشكل جميل -->
        <div id="categoriesBar" class="collapse mb-4">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <!-- زر الكل -->
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


        <!-- ✅ سلة Livewire تبقى كما هي -->
        


        <!-- ✅ البحث -->
        <div class="mb-4 text-end">
            <input type="text"
                   wire:model.debounce.500ms="search"
                   class="form-control text-end"
                   placeholder="بحث باسم المنتج">
        </div>


        <!-- ✅ المنتجات (لم نحذف شيء) -->
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
                                                 style="height:200px;object-fit:cover;"
                                                 alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>

                                @if($product->images->count() > 1)
                                    <button class="carousel-control-prev"
                                            type="button"
                                            data-bs-target="#carouselProduct{{ $product->id }}"
                                            data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>

                                    <button class="carousel-control-next"
                                            type="button"
                                            data-bs-target="#carouselProduct{{ $product->id }}"
                                            data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <img src="{{ asset('images/no-image.png') }}"
                                 class="card-img-top"
                                 style="height:200px; object-fit:cover;">
                        @endif

                        <div class="card-body text-end">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted mb-1">{{ $product->category->name ?? '-' }}</p>
                            <p class="card-text">{{ Str::limit($product->description, 80) }}</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold">
                                    @if(!$product->has_variants)
                                        {{ $product->variants->first()->price ?? '-' }} $
                                    @else
                                        -
                                    @endif
                                </span>

                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-sm"
                                            wire:click="$emit('addToCart', {{ $product->id }})">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>

                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
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

</div>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('home') }}" class="navbar-brand fw-bold">🏠 الرئيسية</a>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#categoriesBar">
                📂 الأقسام
            </button>

            <button class="btn btn-outline-dark position-relative btn-sm" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                🛒 السلة
                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- الأقسام -->
    <div id="categoriesBar" class="collapse mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-dark btn-sm" wire:click="$emit('filterByCategory', null)">الكل</button>
                    @foreach($categories as $category)
                        <button class="btn btn-outline-primary btn-sm" wire:click="$emit('filterByCategory', {{ $category->id }})">
                            {{ $category->name }}
                        </button>
                        @foreach($category->children as $child)
                            <button class="btn btn-outline-secondary btn-sm" wire:click="$emit('filterByCategory', {{ $child->id }})">
                                — {{ $child->name }}
                            </button>
                            @foreach($child->children as $subChild)
                                <button class="btn btn-outline-success btn-sm" wire:click="$emit('filterByCategory', {{ $subChild->id }})">
                                    —— {{ $subChild->name }}
                                </button>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>

<div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
    <div class="offcanvas-header">
        <h5>🛒 سلة المشتريات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <livewire:cart />
    </div>
</div>

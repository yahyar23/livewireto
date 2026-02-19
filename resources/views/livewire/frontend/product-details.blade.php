
<div class="container py-4" dir="rtl">
    <h2>{{ $product->name }}</h2>

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
        </div>
    @endif

    <p>{{ $product->description }}</p>

    <p>السعر: {{ $product->variants->first()->price ?? '-' }} $</p>

    @if($product->variants->count())
        <label>اختر المقاس:</label>
        <select wire:model="selectedVariant" class="form-select w-auto mb-3">
            @foreach($product->variants as $variant)
                <option value="{{ $variant->id }}">
                    {{ $variant->name }} (المتوفر: {{ $variant->stock }})
                </option>
            @endforeach
        </select>
    @endif

    <button class="btn btn-primary" wire:click="$emit('addToCart', {{ $product->id }})">
        أضف إلى السلة
    </button>
</div>

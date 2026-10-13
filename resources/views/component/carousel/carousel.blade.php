@push('css')
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@endpush

@php
    $defaultTags = [
        'SOLAR TIDE',
        'COPPER VEIN',
        'AMBER FLUX',
        'TIDE POOL',
        'GILDED SMOKE',
        'INK BLOOM',
        'OBSIDIAN VELVET',
        'BRONZE HORIZON'
    ];
@endphp

@if(!empty($slideImages) && count($slideImages) > 0)
<section class="mirror-hall-section" id="mirrorHallSection" aria-label="Mirror Hall 3D Showcase">
    <div class="mirror-hall-header">
        <div class="mirror-hall-eyebrow">
            <span class="mirror-hall-pill">BRONZE & TIME · COLLECTION Nº 01</span>
        </div>
        <h2 class="mirror-hall-title">Mirror Hall</h2>
        <p class="mirror-hall-subtitle">Precision automotive abstractions, suspended over still bronze water</p>
    </div>

    <div class="mirror-hall-stage" id="mirrorHallStage">
        <!-- Reflective Pool Floor Glow -->
        <div class="mirror-hall-water-glow" aria-hidden="true"></div>
        <div class="mirror-hall-water-horizon" aria-hidden="true"></div>

        <!-- 3D Carousel Cylinder Track -->
        <div class="mirror-hall-track" id="mirrorHallTrack">
            @foreach ($slideImages as $i => $image)
                @php
                    $altText = $image->slideshow_image_alt ?? '';
                    $isGeneric = empty($altText) || str_contains(strtolower($altText), 'logo') || str_contains(strtolower($altText), 'pngtree');
                    $cardTag = !$isGeneric ? strtoupper($altText) : ($defaultTags[$i % count($defaultTags)]);
                    $imgUrl = route('get.file', ['filename' => $image->slideshow_image_url]);
                @endphp
                <div class="mirror-hall-card" data-index="{{ $i }}" data-original-index="{{ $i }}">
                    <div class="mirror-hall-card-header">
                        <span class="mirror-hall-card-tag">{{ $cardTag }}</span>
                    </div>
                    <div class="mirror-hall-card-body">
                        <div class="mirror-hall-card-sheen" aria-hidden="true"></div>
                        <img class="mirror-hall-card-img" 
                             src="{{ $imgUrl }}" 
                             alt="{{ $cardTag }}" 
                             loading="{{ $i === 0 ? 'eager' : 'lazy' }}" 
                             draggable="false">
                        <div class="mirror-hall-card-overlay">
                            <a href="{{ route('saleProducts') }}" class="mirror-hall-view-btn" aria-label="Explore {{ $cardTag }}">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Mirror reflection on dark pool water -->
                    <div class="mirror-hall-reflection" aria-hidden="true">
                        <div class="mirror-hall-reflection-body">
                            <img class="mirror-hall-reflection-img" 
                                 src="{{ $imgUrl }}" 
                                 alt="" 
                                 loading="lazy" 
                                 draggable="false">
                            <div class="mirror-hall-water-ripples"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Floating Navigation Chevrons -->
        <button class="mirror-hall-nav mirror-hall-prev" id="mirrorHallPrev" type="button" aria-label="Previous Slide">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="mirror-hall-nav mirror-hall-next" id="mirrorHallNext" type="button" aria-label="Next Slide">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <!-- HUD Counter Pill & Pagination Dots -->
    <div class="mirror-hall-hud">
        <div class="mirror-hall-counter" id="mirrorHallCounter" aria-live="polite">
            <span class="mirror-hall-curr" id="mirrorHallCurr">01</span>
            <span class="sep">/</span>
            <span class="mirror-hall-total" id="mirrorHallTotal">{{ sprintf('%02d', count($slideImages)) }}</span>
        </div>
        <div class="mirror-hall-dots" id="mirrorHallDots" role="tablist" aria-label="Slides"></div>
    </div>
</section>
@endif

@push('js')
    <script src="{{ asset('js/mirror-hall.js') }}"></script>
@endpush

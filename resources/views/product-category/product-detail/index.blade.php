@extends('layout')

@php
    $productPrice = $product->product_price_sale ?? $product->product_price;
    $mainImage = !empty($product->product_images[0])
        ? route('get.file', ['filename' => $product->product_images[0]->product_image_path])
        : (!empty($product->post_image_path) ? route('get.file', ['filename' => $product->post_image_path]) : asset('images/logo.png'));
    $cleanDesc = !empty($product->post_content)
        ? \Illuminate\Support\Str::limit(strip_tags($product->post_content), 160)
        : __('message.productMetaDescription', ['name' => $product->product_name, 'price' => $productPrice]);
@endphp

@section('title', $product->product_name . ' PPF Template | $' . $productPrice)
@section('meta_title', $product->product_name . ' PPF Template | $' . $productPrice)
@section('meta_description', __('message.productMetaDescription', ['name' => $product->product_name, 'price' => $productPrice]))
@section('og_image', $mainImage)
@section('og_type', 'product')

@push('schema')
    @include('component.schema.index', [
        'type' => 'product',
        'product' => $product,
        'price' => $productPrice,
        'image' => $mainImage,
        'description' => $cleanDesc,
        'breadcrumbs' => [
            ['name' => __('message.home'), 'url' => url(App::getLocale())],
            ['name' => __('message.productTitle'), 'url' => !empty($categories[0]->url) ? $categories[0]->url : url(App::getLocale())],
            ['name' => $product->product_name, 'url' => url()->current()],
        ]
    ])
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
    <div class="product-detail">
        <div class="container">
            <div class="title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>{{ $product->product_name }}</h4>
                        </div>
                        <div class="col-md-6 col-sm-12 path">
                            <a href="{{ url(App::getLocale() . '/') }}"><i class="fa-solid fa-house"></i></a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="{{ !empty($categories[0]->url) ? $categories[0]->url : url(App::getLocale() . '/') }}">{{ __('message.productTitle') }}</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <span class="current-crumb">{{ $product->product_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    {{-- Interactive Product Image Showcase (Applied to Actual Product Image) --}}
                    <div class="product-showcase-stage" id="productShowcaseStage" data-mode="render" data-finish="gloss-clear">
                        {{-- Top Controls & Mode Switcher --}}
                        <div class="showcase-topbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="showcase-live-pill"><span class="live-pulse-dot"></span> {{ __('message.live3DPreview') }}</span>
                                <span class="showcase-spec-pill d-none d-sm-inline-flex"><i class="fa-solid fa-microchip me-1"></i> {{ __('message.toleranceSpec') }}</span>
                            </div>

                            {{-- Bước 2: Nút gạt Switch sang "Vector Blueprint (CAD Dark Mode)" --}}
                            <div class="d-flex align-items-center gap-2">
                                <div class="showcase-mode-switcher btn-group" role="group" aria-label="Display Mode">
                                    <button type="button" class="btn-mode-toggle active" id="btnModeRender" data-mode="render">
                                        <i class="fa-solid fa-image me-1"></i> {{ __('message.standardMode') }}
                                    </button>
                                    <button type="button" class="btn-mode-toggle" id="btnModeBlueprint" data-mode="blueprint">
                                        <i class="fa-solid fa-vector-square me-1"></i> {{ __('message.vectorBlueprint') }}
                                    </button>
                                </div>
                                <button type="button" class="btn-showcase-action" id="btn360Spin" title="{{ __('message.spin360Title') }}">
                                    <i class="fa-solid fa-rotate"></i>
                                </button>
                                <button type="button" class="btn-showcase-action" id="btnToggleZoom" title="{{ __('message.cadLoupeTitle') }}">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Main Product Image 3D Viewport --}}
                        <div class="showcase-viewport position-relative" id="showcaseViewport">
                            {{-- CAD Blueprint Grid Backdrop (Active in Blueprint Mode) --}}
                            <div class="blueprint-grid-backdrop">
                                <div class="blueprint-grid-lines"></div>
                                <div class="blueprint-corner-cross bc-tl">+</div>
                                <div class="blueprint-corner-cross bc-tr">+</div>
                                <div class="blueprint-corner-cross bc-bl">+</div>
                                <div class="blueprint-corner-cross bc-br">+</div>
                                <div class="blueprint-hud-specs">
                                    <span class="blueprint-label">{{ __('message.cadVectorBlueprint') }}</span>
                                    <span class="blueprint-sub">{{ __('message.zeroStretchSpec') }}</span>
                                </div>
                            </div>

                            {{-- High-Precision CAD Inspection Loupe (Magnifier Reticle) --}}
                            <div class="cad-inspect-loupe d-none" id="cadInspectLoupe">
                                <div class="loupe-reticle-circle" id="loupeCircle">
                                    <div class="loupe-crosshair ch-h"></div>
                                    <div class="loupe-crosshair ch-v"></div>
                                    <div class="loupe-hud-coords" id="loupeCoords">±0.05mm</div>
                                </div>
                            </div>

                            {{-- 3D Perspective Card (Bước 1: Nghiêng nhẹ theo chuột & Ánh kim loại lướt qua) --}}
                            <div class="showcase-perspective-card" id="showcaseCard">
                                {{-- Vệt ánh kim loại lướt qua thân sản phẩm --}}
                                <div class="metallic-sheen-beam" id="metallicSheen"></div>

                                {{-- Viền mí vàng đồng sáng rực (Bước 2) --}}
                                <div class="blueprint-cutline-border"></div>

                                {{-- Ảnh thật của sản phẩm ($mainImage) --}}
                                <div class="product-image-container img-mask">
                                    <img id="mainImage" alt="{{ $product->product_images[0]->product_image_alt ?? ($product->post_image_alt ?? $product->product_name) }}"
                                        src="{{ $mainImage }}"
                                        class="main-product-img">
                                </div>

                                {{-- Đổ bóng sàn 3D --}}
                                <div class="showcase-ground-shadow"></div>
                            </div>
                        </div>

                        {{-- Bước 3: Thử chất liệu (Material Finishes Deck) --}}
                        <div class="showcase-material-deck mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="material-deck-label"><i class="fa-solid fa-swatchbook text-warning me-2"></i><b>{{ __('message.materialSimulatorTitle') }}</b></span>
                                <span class="material-deck-hint small text-muted"><i class="fa-solid fa-hand-pointer me-1"></i> {{ __('message.materialSimulatorHint') }}</span>
                            </div>
                            <div class="row g-2 finish-cards-row">
                                <div class="col-6 col-sm-3">
                                    <div class="card-finish active" data-finish="gloss-clear">
                                        <div class="finish-color-dot dot-gloss-clear"></div>
                                        <div class="finish-info">
                                            <div class="finish-name">{{ __('message.finishGlossClear') }}</div>
                                            <div class="finish-sub">{{ __('message.finishGlossClearSub') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-sm-3">
                                    <div class="card-finish" data-finish="matte-stealth">
                                        <div class="finish-color-dot dot-matte-stealth"></div>
                                        <div class="finish-info">
                                            <div class="finish-name">{{ __('message.finishMatteStealth') }}</div>
                                            <div class="finish-sub">{{ __('message.finishMatteStealthSub') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-sm-3">
                                    <div class="card-finish" data-finish="forged-carbon">
                                        <div class="finish-color-dot dot-forged-carbon"></div>
                                        <div class="finish-info">
                                            <div class="finish-name">{{ __('message.finishForgedCarbon') }}</div>
                                            <div class="finish-sub">{{ __('message.finishForgedCarbonSub') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-sm-3">
                                    <div class="card-finish" data-finish="golden-bronze">
                                        <div class="finish-color-dot dot-golden-bronze"></div>
                                        <div class="finish-info">
                                            <div class="finish-name">{{ __('message.finishGoldenBronze') }}</div>
                                            <div class="finish-sub">{{ __('message.finishGoldenBronzeSub') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ảnh thu nhỏ của sản phẩm (Thumbnails) --}}
                        @if ($product->product_images && count($product->product_images) > 1)
                            <div class="image-thumbnails mt-3 d-flex gap-2 overflow-auto pb-1">
                                @foreach ($product->product_images as $image)
                                    <div class="img-mask">
                                        <img src="{{ route('get.file', ['filename' => $image->product_image_path]) }}"
                                            alt="{{ $image->product_image_alt ?? $product->product_name }}"
                                            class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                            loading="lazy"
                                            data-src="{{ route('get.file', ['filename' => $image->product_image_path]) }}"
                                            data-index="{{ $loop->index }}" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-5 col-sm-12 info">
                    <h2>{{ $product->product_name }}</h2>
                    <div class="view d-flex">
                        <div class="view-in">
                            <i class="fa-solid fa-eye me-1"></i>
                            @if ($product->product_status_views == 0)
                                <span>{{ $product->product_fake_views }} {{ __('message.view') }}</span>
                            @else
                                <span>{{ $product->product_views }} {{ __('message.view') }}</span>
                            @endif
                            <span class="ms-3 text-warning"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> {{ __('message.reviewsCount', ['rating' => '5.0', 'count' => 48]) }}</span>
                        </div>
                    </div>
                    <div class="price">
                        @if ($product->product_price != $product->product_price_sale)
                            <span class="price-sale">${{ $product->product_price }}</span>
                        @endif
                        {{ '$' . $product->product_price_sale }}
                    </div>
                    <div id="post-container" class="product-description-container">
                        {!! $product->post_content !!}
                    </div>
                    <noscript>
                        <div class="product-description-fallback">
                            {!! $product->post_content !!}
                        </div>
                    </noscript>
                    @if (count($categories_detail) > 0)
                        <h6 class="mb-2">{{ __('message.categories') }}:</h6>
                        <div class="d-flex flex-wrap gap-2" id="productCategories">
                            @foreach ($categories_detail as $cat)
                                <a href="{{ $cat->url }}"
                                    class="category-badge d-flex align-items-center border rounded px-2 py-1 text-decoration-none"
                                    style="max-width: 180px;">
                                    <img src="{{ route('get.file', ['filename' => $cat->image_path]) }}"
                                        alt="{{ $cat->image_alt ?? $cat->name }}" width="30" height="30"
                                        loading="lazy"
                                        class="me-2 rounded">
                                    <span class="text-truncate fw-bold">{{ $cat->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Step 4: Pre-Cut File Format Badges with Magnetic Dodge (Repel Physics) --}}
                    <div class="product-file-formats mt-4">
                        <div class="format-header d-flex align-items-center justify-content-between mb-2">
                            <span class="small text-uppercase fw-bold text-warning">
                                <i class="fa-solid fa-file-code me-1"></i> {{ __('message.preCutFormats') }}
                            </span>
                            <span class="small text-muted fst-italic"><i class="fa-solid fa-arrows-to-circle me-1"></i> {{ __('message.magneticRepelHint') }}</span>
                        </div>
                        <div class="repel-badges-deck d-flex flex-wrap gap-2">
                            <div class="repel-badge badge-svg" data-format="SVG" data-repel="true" title="{{ __('message.svgTitle') }}">
                                <span class="badge-icon"><i class="fa-solid fa-bezier-curve"></i></span>
                                <span class="badge-label">SVG</span>
                                <span class="badge-sub">{{ __('message.vectorBadge') }}</span>
                            </div>
                            <div class="repel-badge badge-dxf" data-format="DXF" data-repel="true" title="{{ __('message.dxfTitle') }}">
                                <span class="badge-icon"><i class="fa-solid fa-compass-drafting"></i></span>
                                <span class="badge-label">DXF</span>
                                <span class="badge-sub">{{ __('message.plotterBadge') }}</span>
                            </div>
                            <div class="repel-badge badge-ai" data-format="AI" data-repel="true" title="{{ __('message.aiTitle') }}">
                                <span class="badge-icon"><i class="fa-solid fa-pen-nib"></i></span>
                                <span class="badge-label">AI</span>
                                <span class="badge-sub">{{ __('message.layeredBadge') }}</span>
                            </div>
                            <div class="repel-badge badge-pdf" data-format="PDF" data-repel="true" title="{{ __('message.pdfTitle') }}">
                                <span class="badge-icon"><i class="fa-solid fa-file-pdf"></i></span>
                                <span class="badge-label">PDF</span>
                                <span class="badge-sub">{{ __('message.oneToOnePrintBadge') }}</span>
                            </div>
                            <div class="repel-badge badge-plt" data-format="PLT" data-repel="true" title="{{ __('message.pltTitle') }}">
                                <span class="badge-icon"><i class="fa-solid fa-scissors"></i></span>
                                <span class="badge-label">PLT</span>
                                <span class="badge-sub">{{ __('message.cncBadge') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Add to Cart / Instant Download with Liquid Bronze Shimmer Sweep --}}
                    @if (!empty($user_name))
                        @if ($product->can_download == true)
                            <p class="note mt-3">{{ __('message.downloadNote') }}</p>
                            <div class="down">
                                <form action="{{ route('downloadProduct') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="src_product" value="{{ $product->product_file_path }}">
                                    <input type="hidden" name="zip_file_name" value="{{ $product->product_name }}">
                                    <button type="submit" class="btn-down">
                                        <img src="{{ asset('images/download.png') }}" alt="Download">
                                    </button>
                                </form>
                            </div>
                        @endif
                        @if ($product->can_download == false)
                            <div class="add-cart">
                                <form action="{{ route('cart.add') }}" method="POST" class="form-add-cart"
                                    id="formAddToCart">
                                    @csrf
                                    <input type="hidden" name="product_slug" value="{{ $product->product_slug }}">
                                    <button type="submit" id="addTocart" class="btn-luxury-cart bronze-shimmer-btn">
                                        <i class="fa-solid fa-cart-shopping me-2"></i>
                                        <span>{{ __('message.addToCartBtn') }} • ${{ $productPrice }}</span>
                                    </button>
                                </form>
                                <div class="cart-sub-note mt-2 text-center">
                                    <i class="fa-solid fa-bolt text-warning me-1"></i> {{ __('message.instantDownloadNote') }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="add-cart">
                            <form class="form-add-cart" action="#">
                                <button type="button" id="addTocartNoLogin" class="btn-luxury-cart bronze-shimmer-btn"
                                    onclick="openModalLoginRegis()">
                                    <i class="fa-solid fa-cart-shopping me-2"></i>
                                    <span>{{ __('message.addToCartBtn') }} • ${{ $productPrice }}</span>
                                </button>
                            </form>
                            <div class="cart-sub-note mt-2 text-center">
                                <i class="fa-solid fa-bolt text-warning me-1"></i> {{ __('message.instantDownloadNote') }}
                            </div>
                        </div>
                    @endif

                    {{-- Step 4: Trust Badges & 7-Day Money-Back Guarantee --}}
                    <div class="product-trust-badges mt-4 p-3 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-shield-halved text-warning me-2 fs-5"></i>
                            <span class="fw-bold trust-title">{{ __('message.guaranteeTitle') }}</span>
                        </div>
                        <p class="small trust-desc mb-2">
                            {{ __('message.guaranteeDesc') }}
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-2 pt-2 border-top trust-divider">
                            <span class="badge trust-badge"><i class="fa-solid fa-bolt me-1"></i> {{ __('message.instantDownloadBadge') }}</span>
                            <span class="badge trust-badge"><i class="fa-solid fa-certificate me-1"></i> {{ __('message.zeroStretchBadge') }}</span>
                            <span class="badge trust-badge"><i class="fa-solid fa-lock me-1"></i> {{ __('message.sslEncryptedBadge') }}</span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mt-2 pt-2 border-top trust-divider small trust-desc">
                            <span class="fw-semibold">{{ __('message.paymentMethods') }}</span>
                            <span class="badge trust-badge"><i class="fa-brands fa-paypal me-1"></i> PayPal</span>
                            <span class="badge trust-badge"><i class="fa-brands fa-stripe me-1"></i> Stripe</span>
                            <span class="badge trust-badge"><i class="fa-brands fa-bitcoin me-1"></i> Crypto (BTC, ETH, USDT, SOL)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('component.noticationNoData.loading.index', [])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'add_to_cart_failed',
            'type' => 'error',
        ])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'error',
            'type' => 'error',
        ])
        @include('component.noticationNoData.notification.index', [
            'notification' => 'add_to_cart_success',
            'type' => 'success',
        ])
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script>
        window.productImages = @json($product->product_images).map(image => {
            return {
                ...image,
                product_image_path: routeGetFileTemplate.replace(":filename", image.product_image_path)
            };
        });
        const rawHtml = @json($product->post_content);
        renderSmartContent(rawHtml, "post-container");
    </script>
    <script src="{{ asset('js/product-image.js') }}"></script>
    <script src="{{ asset('js/product-showcase.js') }}"></script>
@endpush

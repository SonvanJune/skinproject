@push('css')
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
@endpush
{{-- <div class="road-divider road-divider-top">
    <div class="road-line"></div>
</div> --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <!-- TEXT -->
            <div class="col-md-6 hero-text">
                <h1 class="display-4 fw-bold">{{__('message.heroBigTitle')}}</h1>
                <p class="lead">{{__('message.heroSmallTitle')}}</p>
                <a href="{{ url(App::getLocale() . '/product-category' . '/' . $categories[0]->slug) }}" class="btn btn-racing btn-lg mt-3">{{__('message.explore')}}&nbsp;<i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- CAR -->
            <div class="col-md-6 position-relative">
                <div class="car-wrapper">
                    <img src="{{ asset('images/hero.png') }}" alt="Car">
                    <div class="car-shadow"></div>
                    <div class="smoke"></div>
                </div>
            </div>
        </div>
    </div>
</section>

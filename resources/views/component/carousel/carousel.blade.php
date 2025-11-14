@push('css')
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@endpush

<div id="carouselExampleAutoplaying" class="carousel slide nor" data-bs-ride="carousel" data-bs-interval="2000">
    @isset($slideImages)
        <div class="carousel-inner">
            @foreach ($slideImages as $i => $image)
                @if ($i == 0)
                    <div class="carousel-item active">
                        <img class="d-block" src="{{route('get.file', ['filename' => $image->slideshow_image_url]) }}" alt="{{ $image->slideshow_image_alt }}">
                    </div>
                @else
                    <div class="carousel-item">
                        <img class="d-block" src="{{route('get.file', ['filename' => $image->slideshow_image_url]) }}"
                            alt="{{ $image->slideshow_image_alt }}">
                    </div>
                @endif
            @endforeach
        </div>
    @endisset
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">{{ __('message.previous') }}</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">{{ __('message.next') }}</span>
    </button>
</div>

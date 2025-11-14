@push('css')
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@endpush

<div class="container">
    <div class="carousel-custom">
        <button class="carousel-custom-button prev"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="carousel-custom-wrapper">
            <div class="carousel-custom-inner">
                @isset($categories)
                    <input type="hidden" id="countCate" value="{{count($categories)}}"/>
                    @foreach ($categories as $item)
                        @if ($item->image_path != null)
                            <div class="carousel-custom-item">
                                <img src="{{route('get.file', ['filename' => $item->image_path])}}" alt="{{$item->image_alt}}">
                            </div>
                        @endif
                    @endforeach
                    <div class="carousel-custom-item-no">
                        <img src="{{asset('images/logo.png')}}" alt="">
                    </div>
                    <div class="carousel-custom-item-no">
                        <img src="{{asset('images/logo.png')}}" alt="">
                    </div>
                @endisset
            </div>
        </div>
        <button class="carousel-custom-button next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <div class="pagination"></div>
</div>


@push('js')
    <script src="{{ asset('js/carousel-drug.js') }}"></script>
@endpush

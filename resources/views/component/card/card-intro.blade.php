@push('css')
    <link rel="stylesheet" href="{{ asset('css/card-intro.css') }}">
@endpush

<div class="card">
    <div class="title-big">
        {{ $titleBig }}
    </div>
    <div class="card-body"
        @if (isset($background)) style="background: url({{ $background }}) {{ $fixed }} ; background-size: cover; background-position: center center;" @endif>
        <div class="container">
            <div class="title-small">
                <h2 class="">{{ $titleSmall }}</h2>
            </div>
            {{-- Items --}}
            <div class="list">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            @if (!empty($children))
                                @foreach (collect($children)->chunk(3) as $chunk)
                                    <div class="d-flex flex-wrap flex-md-nowrap">
                                        @foreach ($chunk as $key => $category)
                                            @if (isset($category->name))
                                                <a class="flex-fill d-flex item" href="{{url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
                                                    <i class="{{ $icon[$key] ?? '' }}"></i>
                                                    <div class="flex-item">{{ $category->name }}</div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('css/brand-item.css') }}">
@endpush

<a class="brand-item" href="{{ url(App::getLocale() . '/product-category' . '/' . $category->slug) }}">
    <img src="{{route('get.file', ['filename' => $category->image_path])}}" alt="{{$category->image_alt}}" srcset="">
    <h4>{{$category->name}}</h4>
</a>
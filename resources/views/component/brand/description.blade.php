@push('css')
    <link rel="stylesheet" href="{{ asset('css/brand-description.css') }}">
@endpush

<ul class="description-brand">
    @foreach ($descriptions as $des)
        <li class="item">{!!$des!!}</li>
    @endforeach
</ul>
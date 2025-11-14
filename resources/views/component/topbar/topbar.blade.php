@push('css')
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
@endpush

<div class="topbar">
    @include('component.topbar.topbar-nav', [])
    @include('component.topbar.topbar-menu', [])
    @include('component.search-modal.index')
</div>

{{-- topbar sm --}}
<div class="topbar-sm">
    @include('component.topbar.topbar-sm', [])
</div>


@push('js')
    <script src="{{ asset('js/topbar.js') }}"></script>
@endpush

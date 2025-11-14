<!-- layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @yield('head')

    @stack('css')
</head>

<body data-route-translate="{{ route('translate') }}" data-route-search="{{ route('search') }}" data-csrf-translate="{{ csrf_token() }}">
    @yield('body')

    @stack('js')
</body>

</html>
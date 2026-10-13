<!-- layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <script>
        (function () {
            const theme = localStorage.getItem('site_theme_mode') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @yield('head')

    @stack('schema')
    @stack('css')
</head>

<body data-route-translate="{{ route('translate') }}" data-route-search="{{ route('search') }}" data-csrf-translate="{{ csrf_token() }}">
    @yield('body')

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    @stack('js')
</body>

</html>
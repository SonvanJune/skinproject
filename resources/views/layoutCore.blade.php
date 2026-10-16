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
    <style>
        html {
            background-color: #070709;
            background: radial-gradient(circle at 18% 12%, rgba(140, 95, 45, 0.15) 0%, transparent 45%),
                        radial-gradient(circle at 85% 65%, rgba(40, 75, 105, 0.12) 0%, transparent 40%),
                        #070709;
            background-attachment: fixed;
            min-height: 100vh;
        }
        html[data-theme="light"] {
            background-color: #faf7f3;
            background: radial-gradient(circle at 18% 12%, rgba(223, 183, 124, 0.22) 0%, transparent 45%),
                        radial-gradient(circle at 85% 65%, rgba(140, 185, 215, 0.16) 0%, transparent 40%),
                        #faf7f3;
            background-attachment: fixed;
        }
        body {
            background-color: transparent !important;
            background: transparent !important;
            min-height: 100vh;
        }
        #bronze-bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #bronze-bg-canvas.is-ready {
            opacity: 1;
        }
        html[data-theme="light"] #bronze-bg-canvas.is-ready {
            opacity: 1;
        }
    </style>
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
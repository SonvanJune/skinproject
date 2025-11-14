<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap-icons.css') }}">

    {{-- Fontawsome icons --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/regular.css') }}">

    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/file-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">

    {{-- Cropper js --}}
    <link rel="stylesheet" href="{{ asset('css/image-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('js/cropperjs/cropper.css') }}">
    <script src="{{ asset('js/cropperjs/cropper.js') }}"></script>

    {{-- Quill Js --}}
    <link href="{{ asset('css/quill/quill.snow.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/ajax/highlight.min.js') }}"></script>
    <script src="{{ asset('js/quill/quill.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/ajax/atom-one-dark.min.css') }}" />
    <script src="{{ asset('js/katex/katex.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/katex/katex.min.css') }}" />
    <script src="{{ asset('js/quill/quill-resize-module.js') }}"></script>

    {{-- Axios --}}
    <script src="{{ asset('js/axios/axios.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    @yield('link')
    <title>@yield('title', 'TDC EVENTS')</title>

</head>

<body>
    @stack('js')
    <div class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
        @include('component.admin.header.admin-header')
        @include('component.admin.sidebar.admin-sidebar')
        <main id="content" class="main">
            <div class="content container-fluid">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        const routeGetFileTemplate = @json(route('get.file', ['filename' => ':filename']));
    </script>
    <script src="{{ asset('js/admin/block-width.js') }}"></script>
    <script src="{{ asset('js/bootstraps/bootstrap.bundle.min.js') }}"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <script>
        const defaultImagePath = "{{ asset('images/image-default.png') }}";
    </script>
    <div id="modalContainer"></div>
    <script src="{{ asset('js/file-manager/file-manager.js') }}"></script>

    <script>
        const sideToggler = document.getElementById('side-toggler');
        const headerToggler = document.getElementById('header-toggler');
        const navbar = document.querySelector('.navbar-vertical-fixed');
        let tabletOrBelow = window.innerWidth
        window.addEventListener('resize', function() {
            tabletOrBelow = window.innerWidth
            if (!isTabletOrBelow()) {
                navbar.style.marginLeft = 0;
            } else {
                navbar.style.marginLeft = "-18rem";
            }
        });

        function isTabletOrBelow() {
            return tabletOrBelow <= 1024;
        }

        if (!isTabletOrBelow()) {
            navbar.style.marginLeft = 0;
        };

        function toggleNavbar() {
            if (!isTabletOrBelow()) {
                return
            };


            const isCollapsed = navbar.style.marginLeft === '-18rem';

            navbar.style.marginLeft = isCollapsed ? '0' : '-18rem';

            const container = document.querySelector('.has-navbar-vertical-aside');
            const existingOverlay = container.querySelector('.navbar-vertical-aside-mobile-overlay');

            if (isCollapsed) {
                if (!existingOverlay) {
                    const overlay = document.createElement('div');
                    overlay.classList.add('navbar-vertical-aside-mobile-overlay');
                    overlay.style.opacity = '1';
                    container.appendChild(overlay);

                    overlay.addEventListener('click', () => {
                        navbar.style.marginLeft = '-18rem';
                        container.removeChild(overlay);
                    });
                }
            } else if (existingOverlay) {
                container.removeChild(existingOverlay);
            }
        }

        sideToggler.addEventListener('click', toggleNavbar);
        headerToggler.addEventListener('click', toggleNavbar);
    </script>

    <script>
        document.getElementById('openFileManagerModal').addEventListener('click', () => {
            renderModal('folderPathModal');
        });
    </script>
    <script src="{{ asset('js/block-dev-tool.js') }}"></script>
</body>

</html>

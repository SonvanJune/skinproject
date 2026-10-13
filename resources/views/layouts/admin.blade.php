<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap & Icons (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Font Awesome (CDN) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/file-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">

    {{-- Cropper js (CDN) --}}
    <link rel="stylesheet" href="{{ asset('css/image-editor.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    {{-- Quill, Highlight, KaTeX (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css" />
    <script src="{{ asset('js/quill/quill-resize-module.js') }}"></script>

    {{-- Axios (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    @yield('link')
        <title>@yield('title', 'Admin')</title>
        <link rel="icon" type="image/png" href="{{asset('images/logo.png')}}">

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
    
    <script src="{{ asset('js/handleHtml.js') }}"></script>
    <script>
        const routeGetFileTemplate = @json(route('get.file', ['filename' => ':filename']));
    </script>
    <script src="{{ asset('js/admin/block-width.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    {{-- <script src="{{ asset('js/block-dev-tool.js') }}"></script> --}}
</body>

</html>

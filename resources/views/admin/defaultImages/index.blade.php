@extends('layouts.admin')

@section('title', 'Admin-Images')

@push('css')
    <style>
        .image-card {
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            padding: 10px;
        }

        .image-card img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Default Image Manager</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            @foreach ($images as $img)
                <div class="col-md-3">
                    <div class="card image-card shadow-sm">
                        <img id="preview-{{ $img['name'] }}"
                            src="{{ asset($img['path']) }}?v={{ filemtime(public_path($img['path'])) }}"
                            alt="{{ $img['name'] }}" class="img-fluid rounded mx-auto d-block"
                            style="max-height:150px; object-fit:contain;">
                        <div class="mt-2">
                            <form action="{{ route('admin.defaultImages.update', $img['name']) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="image_file" class="form-control mb-2"
                                    onchange="previewImage(this, '{{ $img['name'] }}', '{{ $img['name'] }}-btn')"
                                    accept="image/*">
                                <button id="{{ $img['name'] }}-btn" class="btn btn-primary btn-sm w-100" disabled>
                                    Update
                                </button>
                            </form>
                            <small class="text-muted d-block mt-1">{{ $img['name'] }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    <script>
        function previewImage(input, imageName, btnId) {
            const preview = document.getElementById('preview-' + imageName);
            const btn = document.getElementById(btnId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
                btn.disabled = false;
            } else {
                preview.src = preview.dataset.original + '?v=' + new Date().getTime();
                btn.disabled = true;
            }
        }

        document.querySelectorAll('.image-card img').forEach(img => {
            img.dataset.original = img.src.split('?')[0];
        });
    </script>
@endpush

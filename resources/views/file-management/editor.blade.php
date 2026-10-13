@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/file-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css" />
    <script src="{{ asset('js/quill/quill-resize-module.js') }}"></script>
@endsection

@section('title', 'Editor')

@section('content')

    <div class="container">
        <!-- Include Editor -->
        @include('component.editor.editor')

        <!-- Include Image Modal -->
        @include('component.editor.file-manager')

    </div>

@endsection

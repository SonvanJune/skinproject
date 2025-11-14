@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/file-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">
    <script src="{{ asset('js/axios/axios.min.js') }}"></script>

    <link href="{{ asset('css/quill/quill.snow.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/ajax/highlight.min.js') }}"></script>
    <script src="{{ asset('js/quill/quill.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/ajax/atom-one-dark.min.css') }}" />
    <script src="{{ asset('js/katex/katex.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/katex/katex.min.css') }}" />
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

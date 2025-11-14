@extends('layout')

@section('title', $category->name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
@endpush

@section('content')
    <div class="brand">
        @if ($description != null)
            <div class="image-type2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <img src="{{ route('get.file', ['filename' => $category->image_path]) }}" alt="{{ $category->image_alt }}" srcset="">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h2>
                                {{ $category->name }}
                            </h2>
                            @include('component.brand.description', [
                                'descriptions' => $description,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="image-type1">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <img src="{{ route('get.file', ['filename' => $category->image_path]) }}" alt="{{ $category->image_alt }}" srcset="">
                            <h2>{{ $category->name }}</h2>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @include('brand.brand-list', [
            'categories' => $category_child,
        ])
    </div>
@endsection

@push('js')
    {{-- Import js if have --}}
@endpush

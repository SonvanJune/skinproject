@extends('product-category.layout')

@section('title', $category_detail->name)
@section('name', $category_detail->name)
@section('root', __('message.categories'))
@section('nameSmall', $category_detail->name)

@push('css')
@endpush

@section('main')
    @include('product-category.product-list.products', [
        'noteOfCategory' => __('message.noteOfCategory'),
        'products' => $list_product,
    ])
    @include('component.noticationNoData.loading.index', [])
    @include('component.noticationNoData.notification.index', ['notification' => 'error' , 'type' => 'error'])
    @include('component.noticationNoData.notification.index', ['notification' => 'success', 'type' => 'success'])
@endsection

@push('js')
@endpush

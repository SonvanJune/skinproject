@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'File manager')

@section('content')

    {{-- <span class="btn btn-white btn-sm" id="openModal2" data-bs-toggle="modal" data-bs-target="#fileModal2">File
        Manager 2</span>
    <x-file-manager modalId="fileModal2" title="Modal 2 Title" />
    <span class="btn btn-white btn-sm" id="openModal1" data-bs-toggle="modal" data-bs-target="#fileModal1">File
        Manager 1</span>
    <x-file-manager modalId="fileModal1" title="Modal 1 Title" /> --}}

    <!-- Nút mở các modal -->
<span class="btn btn-white btn-sm" id="openModal2">File Manager 2</span>
<span class="btn btn-white btn-sm" id="openModal1">File Manager 1</span>

<!-- Modal Container -->
<div id="modalContainer"></div>

<script>
    document.getElementById('openModal1').addEventListener('click', () => {
    renderModal('fileModal1', 'Modal 1 Title');
});

document.getElementById('openModal2').addEventListener('click', () => {
    renderModal('fileModal2', 'Modal 2 Title');
});



</script>

<script src="{{asset('js/file-manager/file-manager.js')}}"></script>
    

@endsection

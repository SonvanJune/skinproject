@extends('layouts.admin')

@section('title', 'SlideShows')

@section('content')
    <style>
        /* #productTable th:last-child, #productTable td:last-child {
                    position: sticky;
                    right: -30px;
                    z-index: 10;
                }
                #productTable td:last-child {
                    background: #fff
                } */
    </style>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">SlideShows <span
                        class="badge bg-soft-dark text-dark ms-2">{{ count($slideshowImages) }}</span></h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('admin.slideshows.create') }}"><i class="bi bi-plus-circle me-2"></i>
                    Add
                    SlideShows</a>
            </div>

        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                <table id="productTable"
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer"
                    role="grid" aria-describedby="datatable_info">
                    <thead class="thead-light">
                        <tr>
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label="Price: activate to sort column ascending" style="width: 85px;">No.</th>
                            <th style="width: 20px;">Actions</th>
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label="Price: activate to sort column ascending" style="width: 85px;">Index</th>
                            <th rowspan="1" colspan="1" style="width: 50px;">Image</th>
                            <th style="width: 85px;">Image alt</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($slideshowImages as $index => $slideshow)
                            <tr role="row">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>
                                <td>
                                    <a class="btn btn-white btn-sm"
                                        href="{{ route('admin.slideshows.edit', ['slideshow_image_id' => $slideshow->slideshow_image_id]) }}">
                                        <i class="bi-pencil-fill me-1"></i>
                                    </a>
                                    <form
                                        action="{{ route('admin.slideshows.delete', ['slideshow_image_id' => $slideshow->slideshow_image_id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-white btn-sm" href="#"
                                            onclick="event.preventDefault(); confirmDelete(this);">
                                            <i class="bi-trash dropdown-item-icon"></i>
                                        </a>
                                    </form>
                                </td>
                                <td>{{ $slideshow->slideshow_image_index }}</td>
                                <td>
                                    <div class="slide-image">
                                        <img src="{{ route('get.file', ['filename' => $slideshow->slideshow_image_url]) }}"
                                            alt="{{ $slideshow->slideshow_image_alt}}"
                                            title="{{$slideshow->slideshow_image_url}}"
                                            style="border-radius: 0; width: 100px; height: 100px; object-fit: contain;"
                                            onerror="this.onerror=null; this.src='{{ asset('images/avatars/default_avatar.jpg') }}'">
                                    </div>
                                </td>
                                <td class="">{{ $slideshow->slideshow_image_alt }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table -->
    </div>
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'slideshow Image',
    ])

    <script>
        function confirmDelete(element) {
            const form = element.closest("form");

            const modal = new bootstrap.Modal(
                document.getElementById("deleteConfirmationModal")
            );
            modal.show();

            document.getElementById("confirmDeleteBtn").onclick = function() {
                form.submit();
                modal.hide();
            };
        }
    </script>
@endsection

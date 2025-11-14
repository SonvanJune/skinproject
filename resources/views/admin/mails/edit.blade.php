@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Mail')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.mails') }}">Mails</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Mail</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Mail : {{ $getMailDTO->mail_name }}</h1>

                <div class="mt-2">
                    <a class="text-body" href="#" onclick="openView()" data-bs-toggle="modal"
                        data-bs-target="#view-mail-modal">
                        <i class="bi-eye me-1"></i> Preview
                    </a>
                </div>
            </div>
            <!-- End Col -->
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

    <form id="mailForm" class="row needs-validation" novalidate
        action="{{ route('admin.mails.update', ['mail_id' => $getMailDTO->mail_id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="col-9 mb-lg-0">
            <!-- Card Raw mail -->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Raw Mail</h4>
                </div>
                <!-- End Header -->

                <!-- Include Editor -->
                @include('component.editor.editor')

                <!-- Body -->
                <div class="card-body">
                    <div class="mail-content"></div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Raw mail -->

            <!-- Button submit -->
            <div class="d-grid gap-2">
                <button type="submit" onclick="processBeforeSubmit(this)" class="btn btn-primary w-full">Save</button>
            </div>

        </div>
        <!-- End Col -->

        <div class="col-3">
            <!-- Card -->
            <div class="card mb-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Keywords</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    @foreach ($getMailDTO->required_attributes as $key => $value)
                        <!-- Required attributes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ '{' . '{ $' . $key . ' }' . '}' }}</label> <span
                                class="text-danger">*</span>
                            <textarea type="text" class="form-control" rows="2" disabled>{{ $value }}</textarea>
                        </div>
                    @endforeach

                    @foreach ($getMailDTO->attributes as $key => $value)
                        <!-- Attributes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ '{' . '{ $' . $key . ' }' . '}' }}</label>
                            <textarea type="text" class="form-control" rows="2" disabled>{{ $value }}</textarea>
                        </div>
                    @endforeach
                </div>
                <!-- Body -->
            </div>
            <!-- End Card -->
        </div>
        <!-- End Col -->

        <input type="text" name="content" readonly hidden id="input-content">
    </form>

    {{-- Modal to view mail --}}
    <div class="modal fade" id="view-mail-modal" tabindex="-1" aria-labelledby="View Mail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" style="height: auto">
                <div class="row p-3">
                    <div class="text-end">
                        <button type="button" class="btn-close mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="mail-example-content"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}

    <script>
        //editor
        const editor = quill.root;
        editor.innerHTML = @json($getMailDTO->content);
    </script>

    <script>
        const exampleContent = document.querySelector('.modal .mail-example-content');
        const contentInput = document.querySelector('#input-content');

        function openView() {
            fetch(@json(route('admin.mails.render')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        mail_content: quill.root.innerHTML,
                    }),
                })
                .then(r => r.text())
                .then(data => {
                    exampleContent.innerHTML = data;
                })
                .catch(e => console.log)
        }

        function processBeforeSubmit(element) {
            //get the content from editor
            contentInput.value = quill.root.innerHTML;

            //submit form
            element.closest('form').submit();
        }
    </script>
@endsection

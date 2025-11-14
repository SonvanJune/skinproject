@extends('layouts.admin')

@section('title', 'Create Tracking Code')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/code_editor.css') }}">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.tracking-codes') }}">Tracking Codes</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Tracking Code</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Tracking Code</h1>
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

    <form id="post-form" class="row needs-validation" novalidate action="{{ route('admin.tracking-codes.store') }}"
        method="POST">
        @csrf
        @method('POST')
        <div class="col-12 mb-3 mb-lg-0">
            <!-- Card Post information-->
            <div class="card mb-3 mb-lg-5">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Tracking Code Information</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <input type="text" readonly hidden name="tracking_code">

                    <input type="number" readonly hidden name="tracking_code_type">

                    <div id="code-editor"></div>
                </div>
                <!-- Body -->
            </div>
            <!-- End Card Post information -->

        </div>
        <!-- End Col -->

        <!-- Button submit -->
        <div class="col-12">
            <div class="d-grid gap-2">
                <button type="submit" onclick="event.preventDefault(); confirmSaveAfterStore(this);"
                    class="btn btn-primary w-full">Save</button>
            </div>
        </div>
    </form>

    @include('component.modal.ConfirmModal', [
        'subject' => 'tracking codes',
        'action' => 'save new tracking code',
    ])

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    {{-- code editor --}}
    <script>
        const editor = document.querySelector('#code-editor');
        const codeInput = document.querySelector('input[name="tracking_code"]');
        const typeInput = document.querySelector('input[name="tracking_code_type"]');

        // init the quill editor 
        const quill = new Quill(`#code-editor`, {
            modules: {
                syntax: true,
                toolbar: [
                    ['code-block']
                ]
            },
            theme: 'snow'
        });

        // Set the initial value for the code input
        quill.setContents([{
            insert: "/** Paste your code here **/\n\n\n\n",
            attributes: {
                'code-block': true,
            }
        }]);

        // set the relavent language
        document.querySelectorAll(`#code-editor .ql-code-block`)?.forEach(e => e?.setAttribute(
            'data-language', 'javascript'));

        // Process the type and code before request to store 
        function confirmSaveAfterStore(element) {
            codeInput.value = quill.getText();

            // Set the type based on the selected language in the code editor
            switch (document.querySelector(`#code-editor .ql-code-block`).dataset.language) {
                case 'xml':
                    typeInput.value = @json($HTML_TYPE);
                    break;

                case 'css':
                    typeInput.value = @json($CSS_TYPE);
                    break;

                case 'javascript':
                    typeInput.value = @json($JS_TYPE);
                    break;
            }

            // Submit the form after storing the tracking code
            const form = element.closest('form');

            const modal = new bootstrap.Modal(
                document.getElementById(
                    "confirmationModal"
                )
            );
            modal.show();

            document.getElementById(
                "confirmBtn"
            ).onclick = function() {
                form.submit();
                modal.hide();
            };
        }
    </script>

@endsection

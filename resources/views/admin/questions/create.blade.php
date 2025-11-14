@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Create Question')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('admin.questions') }}">Questions</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Question</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Add Question</h1>
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

    <form action="{{ route('admin.questions.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Question information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <label for="question-text" class="form-label">Question Text</label>
                    <textarea oninput="createNewQuestion(this.value)" rows="5" type="text" class="form-control"
                        placeholder="Type the security question here..." id="question-text" name="question_text" required></textarea>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="question-text"></div>
            </div>
        </div>

        <div class="mt-3 text-end">
            <button type="submit" onclick="event.preventDefault(); confirmSave(this);" class="btn btn-primary btn-sm">Save
                changes</button>
        </div>
    </form>

    @include('component.modal.ConfirmModal', [
        'subject' => 'security questions',
        'action' => 'save new question',
    ])

    <script>
        /** 
         * to show modal to confirm saving
         */
        function confirmSave(element) {
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

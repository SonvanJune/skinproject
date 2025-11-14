@extends('layouts.admin')

@section('link')
    <link rel="stylesheet" href="{{ asset('css/admin/mutil-select.css') }}">
@endsection

@section('title', 'Edit Question')

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
                        <li class="breadcrumb-item active" aria-current="page">Edit Question
                            {{ $duplicated ? '(Duplicated)' : '' }}</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Edit Question {{ $duplicated ? '(Duplicated)' : '' }}</h1>

                <div class="mt-2">
                    @if (!$duplicated)
                        <a class="text-body me-2"
                            href="{{ route('admin.questions.edit', ['id' => $question->question_id, 'duplicated' => true]) }}">
                            <i class="bi-clipboard me-1"></i> Duplicate
                        </a>
                    @endif
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

    <form action="{{ route('admin.questions.update', ['duplicated' => $duplicated]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <h4 class="card-header-title">Question information</h4>
            </div>
            <!-- End Header -->

            <div class="card-body">
                <div class="mb-3">
                    <input id="edit-question-id" name="question_id" hidden readonly value="{{ $question->question_id }}">

                    <label for="edit-question-text" class="form-label">Question Text</label>
                    <textarea oninput="updateQuestion(this.value)" type="text" class="form-control" id="edit-question-text"
                        name="question_text" required rows="5" placeholder="Type the security question here...">{{ $question->question_text }}</textarea>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="question-text"></div>
            </div>
        </div>

        <div class="mt-3 text-end">
            <button type="submit" onclick="event.preventDefault(); confirmEdit(this);" class="btn btn-primary btn-sm">Save
                changes</button>
        </div>
    </form>

    @include('component.modal.ConfirmModal', [
        'subject' => 'security questions',
        'action' => 'save after editing question',
    ])

    <script>
        /** 
         * to show modal to confirm saving
         */
        function confirmEdit(element) {
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

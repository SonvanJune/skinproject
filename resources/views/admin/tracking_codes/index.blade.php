@extends('layouts.admin')

@section('title', 'Admin-Tracking Codes')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/code_editor.css') }}">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Tracking Codes <span
                        class="badge bg-soft-dark text-dark ms-2">{{ $paginatedDTO->totalArr }} items</span></h1>
            </div>

            <div class="col-sm-auto">
                @php
                    $canBeAdded = $paginatedDTO->totalArr !== 3;
                @endphp
                <a class="btn btn-primary {{ $canBeAdded ? '' : 'disabled' }}"
                    href="{{ $canBeAdded ? route('admin.tracking-codes.create') : '#' }}"><i
                        class="bi bi-plus-circle me-2"></i> Add
                    Tracking Code</a>
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

    @if (session('warning'))
        <div class="alert alert-warining">
            {{ session('warning') }}
        </div>
    @endif

    @php
        $paginationRoute = '/admin/tracking-codes?';
        $paginationRoute .= 'per_page=' . $paginatedDTO->per_page;
        $paginationRoute .= '&key=' . $paginatedDTO->key;
        $paginationRoute .= '&page=';

        $offset = 1;
        $bridge = 3;
        $has_first_offset = false;
        $has_last_offset = false;
    @endphp

    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Dropdown -->
                @include('component.filter-column.index')
                <!-- End Dropdown -->
            </div>
        </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                <table id="role-table"
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer"
                    role="grid" aria-describedby="datatable_info" style="width: 1271px;">
                    <thead class="thead-light">
                        <tr id="column-in-tabel-filter-container">
                            <th class="sorting" rowspan="1" colspan="1"
                                aria-label='Column: activate to sort column ascending' style="width: 85px;">
                                Column</th>

                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions"
                                style="width: 115px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($paginatedDTO->data as $index => $tracking_code)
                            <tr role="row" class="odd">
                                <td>
                                    <button type="button" class="btn btn-primary py-1 px-2" disabled>
                                        {{ $index + 1 }}
                                    </button>
                                </td>

                                <td>
                                    <!-- Edit Link -->
                                    <button class="btn btn-white btn-sm index-{{ $index }} btn-edit"
                                        onclick="enableEditor({{ $index }}, this)"
                                        data-code="{{ $tracking_code->tracking_code }}"
                                        data-language="{{ $tracking_code->tracking_code_language }}">
                                        <i class="bi bi-pencil-fill me-1"></i>
                                    </button>
                                    <!-- End Edit Link -->

                                    <!-- Delete Link -->
                                    <form
                                        action="{{ route('admin.tracking-codes.destroy', ['id' => $tracking_code->tracking_code_id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-white btn-sm" href="#"
                                            onclick="event.preventDefault(); confirmDelete(this);">
                                            <i class="bi-trash dropdown-item-icon"></i>
                                        </a>
                                    </form>
                                    <!-- End Delete Link -->
                                </td>

                                <td class="c0 search-cell">{{ $tracking_code->tracking_code_type }}</td>

                                <td class="c1">{{ $tracking_code->created_at }}
                                </td>

                                <td class="c2">{{ $tracking_code->updated_at }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <div class="code-editor index-{{ $index }}"
                                        data-code="{{ $tracking_code->tracking_code }}"
                                        data-language="{{ $tracking_code->tracking_code_language }}"></div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4">
                                    <div style="display: none;" class="edit-wrapper index-{{ $index }}">
                                        <form action="{{ route('admin.tracking-codes.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <input type="text" readonly hidden
                                                value="{{ $tracking_code->tracking_code_id }}" name="tracking_code_id">
                                            <input type="text" readonly hidden class="index-{{ $index }}"
                                                name="tracking_code" id="tracking_code-{{ $index }}">

                                            <input type="text" readonly hidden
                                                value="{{ $tracking_code->tracking_code_type }}" name="tracking_code_type"
                                                id="tracking_code_type-{{ $index }}">

                                            <button type="submit"
                                                onclick="event.preventDefault(); confirmSaveAfterEdit(this, {{ $index }});"
                                                class="btn btn-primary btn-sm save-button">Save
                                                Changes</button>

                                            <button type="button"
                                                onclick="enableEditor({{ $index }}, document.querySelector('button.btn-edit.index-{{ $index }}'));"
                                                class="btn btn-secondary btn-sm discard-button">Discard
                                                Changes</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table -->

    </div>

    {{-- code editor --}}
    @include('component.modal.confirmModal', [
        'subject' => 'tracking codes',
        'action' => 'save after editing',
    ])
    <script>
        //elements
        const editors = document.querySelectorAll('.code-editor');
        const saveButtons = document.querySelectorAll('.save-button');
        const discardButtons = document.querySelectorAll('.discard-button');
        const editWrappers = document.querySelectorAll('.edit-wrapper');
        const quills = [];

        // initialize quill editors
        editors.forEach((editor, index) => {
            const quill = new Quill(`.code-editor.index-${index}`, {
                modules: {
                    syntax: true,
                    toolbar: [
                        ['code-block']
                    ]
                },
                theme: 'snow'
            });

            // set content for editors
            quill.setContents(editor.dataset.code
                .replace(/\\\\n/g, "\\n")
                .replace(/\\n/g, "\n")
                .split("\n").map(
                    line => ({
                        insert: line + "\n",
                        attributes: {
                            'code-block': true,
                        }
                    })));

            // disabled editor (readonly)
            quill.enable(false);

            // set the relavent language
            document.querySelectorAll(`.code-editor.index-${index} .ql-code-block`)?.forEach(e => e?.setAttribute(
                'data-language', editor
                .dataset.language));

            quills.push(quill);
        });

        /**
         * Active the editor 
         * 
         * @param {int} index
         * @param {Node} target (this element)
         */
        function enableEditor(index, target) {
            //enable the editor to edit
            const enableStatus = quills[index].isEnabled();

            //toggle the edit/save/discard buttons
            if (enableStatus) {
                editWrappers[index].style.display = 'none';
                target.children[0].className = target.children[0].className.replace('bi-x-lg', 'bi-pencil-fill');
            } else {
                editWrappers[index].style.display = 'unset';
                target.children[0].className = target.children[0].className.replace('bi-pencil-fill', 'bi-x-lg');
                quills[index].focus();
                quills[index].setSelection(quills[index].getLength());
            }

            quills[index].enable(!enableStatus);
        }

        function confirmSaveAfterEdit(element, index) {
            const form = element.closest('form');

            const modal = new bootstrap.Modal(
                document.getElementById(
                    "confirmationModal"
                )
            );
            modal.show();
            code = quills[index].editor.delta.ops.map(op => op.insert).join('');
            let inputType = document.getElementById('tracking_code_type-' + index);
            switch (inputType.value) {
                case 'CSS':
                    inputType.value = "2";
                    break;
                case 'Javascript':
                    inputType.value = "3";
                    break;
                case 'HTML':
                    inputType.value = "1";
                    break;
                default:
                    break;
            }

            inputCode = document.getElementById('tracking_code-' + index);
            inputCode.value = code;

            document.getElementById(
                "confirmBtn"
            ).onclick = function() {
                form.submit();
                modal.hide();
            };
        }
    </script>

    {{-- comfirm detete --}}
    @include('component.modal.deleteConfirmModal', [
        'subject' => 'tracking codes',
    ])
    <script src="{{ asset('js/confirm-delete.js') }}"></script>

    {{-- processing the filter columns --}}
    <script>
        //declare variables for columns 
        const columns = ['Tracking Code Type', 'Created Date', 'Updated Date'];
        //key to save into localStorage
        const LOCAL_KEY = "SKIN_PROJECT_TRACKING_CODE_TABLE_COLUMN_FILTER";
    </script>
    <script src="{{ asset('js/filter-column.js') }}"></script>

@endsection

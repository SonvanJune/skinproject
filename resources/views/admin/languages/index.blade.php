@extends('layouts.admin')

@section('title', 'Admin Languages')

@section('content')
    <div class="container">
        <h2 class="mb-4">🌐 Language Manager</h2>

        <div class="mb-3">
            @foreach ($translations as $lang => $items)
                <span class="badge bg-primary me-2">
                    {{ strtoupper($lang) }}: {{ count($items) }} keys
                </span>
            @endforeach
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="languageForm" action="{{ route('admin.languages.update') }}" method="POST">
            @csrf

            <ul class="nav nav-tabs" id="langTabs" role="tablist">
                @foreach ($languages as $lang)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $lang }}-tab"
                            data-bs-toggle="tab" data-bs-target="#{{ $lang }}" type="button" role="tab">
                            {{ strtoupper($lang) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content mt-3" id="langTabContent">
                @foreach ($translations as $lang => $items)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $lang }}"
                        role="tabpanel">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Key</th>
                                    <th>Value ({{ strtoupper($lang) }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $key => $value)
                                    <tr>
                                        <td style="width: 35%"><code>{{ $key }}</code></td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="translations[{{ $lang }}][{{ $key }}]"
                                                value="{{ $value }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            <div class="bt-save border-top p-3">
                <button type="submit" class="btn btn-success btn-lg w-100" disabled id="saveBtn">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/admin/language-table.js') }}"></script>
@endsection

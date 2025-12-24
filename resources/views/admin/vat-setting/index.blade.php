@extends('layouts.admin')

@section('title', 'VAT Setting')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header fw-bold">
                VAT Configuration
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.vat.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">VAT Type</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="percent"
                                {{ $vat['type'] === 'percent' ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Percentage (%)
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" value="amount"
                                {{ $vat['type'] === 'amount' ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Fixed Amount ($)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">VAT Value</label>
                        <input type="number" step="0.01" class="form-control" name="value"
                            value="{{ $vat['value'] }}">
                    </div>

                    <button class="btn btn-primary">Save</button>
                </form>

                <hr>
                <strong>Current file content:</strong>
                <pre>{{ print_r($vat, true) }}</pre>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Admin Maintenance Setting')

@section('content')
<div class="container mt-5">
    <h2>⚙️ Maintenance Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.maintenance.update') }}" method="POST">
        @csrf

        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="status" value="on" id="toggleSwitch"
                   {{ $status === 'on' ? 'checked' : '' }}>
            <label class="form-check-label" for="toggleSwitch">Enable Website</label>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection

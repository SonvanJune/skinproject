@extends('layouts.admin')

@section('title', 'Admin Payment Snapshot')

@section('content')
    <div class="container mt-5">
        <h3 class="mb-4">📄 Payment Snapshot (PDF Files)</h3>

        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($files as $index => $file)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $file->getFilename() }}</td>
                        <td>
                            <a href="{{ route('admin.payment.view', $file->getFilename()) }}" target="_blank"
                                class="btn btn-sm btn-primary">
                                👁 View
                            </a>

                            <a href="{{ route('admin.payment.download', $file->getFilename()) }}" class="btn btn-sm btn-success">
                                ⬇ Download
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No PDF files found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

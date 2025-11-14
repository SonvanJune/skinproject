<form class="input-per-pages" action="{{ route($route) }}">
    @foreach (request()->except('per_page') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <button type="button" class="btn btn-white w-100" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi-list-task me-1"></i> <input type="number" step="1" min="1"
            max="{{ $paginatedDTO->totalArr }}" class="text-end border-0 form2-control" name="per_page"
            value="{{ $paginatedDTO->total }}">
        /
        <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $paginatedDTO->totalArr }}</span>
    </button>
</form>

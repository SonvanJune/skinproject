<form action="{{ route($route) }}">
    <div class="input-group input-group-merge input-group-flush">
        <div class="input-group-prepend input-group-text">
            <i class="bi-search"></i>
        </div>

        @foreach (request()->except('key') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <input type="hidden" name="page" value="1">

        <input oninput="search(this.value)" id="search-input" name="key" type="search" class="form-control"
            value="{{ request()->get('key') }}" placeholder="Search..." aria-label="Search...">
    </div>
</form>

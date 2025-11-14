@if (session($notification))
    <div class="page-overlay"></div>
    @if ($type == 'success')
        <div class="alert alert-success alert-dismissible fade show zoom-in" role="alert">
            <span class="translate-text">{{ session($notification) }}</span>
            <button type="button" class="btn-close" onclick="closeAlert()" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @else
        @if ($type == 'warning')
            <div class="alert alert-warning alert-dismissible fade show zoom-in" role="alert">
                <span class="translate-text">{{ session($notification) }}</span>
                <button type="button" class="btn-close" onclick="closeAlert()" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @else
            <div class="alert alert-danger alert-dismissible fade show zoom-in" role="alert">
                <span class="translate-text">{{ session($notification) }}</span>
                <button type="button" class="btn-close" onclick="closeAlert()" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif
    @endif
@endif

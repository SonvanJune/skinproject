<div id="notiSuc" class="d-none">
    <div class="page-overlay-noti"></div>
    <div id="alert-box" class="alert alert-info alert-dismissible fade show zoom-in" role="alert">
        <!-- Loading spinner -->
        <div class="d-flex align-items-center">
            <strong class="me-2">{{ __('message.processing') }}</strong>
            <div class="spinner-border spinner-border-sm text-white" role="status">
                <span class="visually-hidden">{{ __('message.loading') }}</span>
            </div>
        </div>
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    </div>
</div>
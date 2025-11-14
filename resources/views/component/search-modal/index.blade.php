<div id="search-modal" class="search-modal p-3">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status" id="searchSpinner">
            <span class="visually-hidden">{{ __('message.loading') }}</span>
        </div>
    </div>
    <button type="button" class="btn-close" aria-label="Close" onclick="closeSearch()"></button>
    <div class="search-modal-content" id="search-modal-content">
    </div>
</div>

<div class="dropdown">
    <button type="button" class="btn btn-white w-100" id="showHideDropdown" data-bs-toggle="dropdown" aria-expanded="false"
        data-bs-auto-close="outside">
        <i class="bi-table me-1"></i> Columns <span class="badge bg-soft-dark text-dark rounded-circle ms-1 column-count">0</span>
    </button>

    <div class="dropdown-menu dropdown-menu-end dropdown-card" aria-labelledby="showHideDropdown" style="width: 15rem;">
        <div class="card card-sm">
            <div class="card-body">
                <div class="d-grid gap-3" id="column-filter-container">
                    <!-- Form Switch -->
                    <label class="row form-check form-switch" for="toggleColumn_product">
                        <span class="col-8 col-sm-9 ms-0">
                            <span class="me-2">Column</span>
                        </span>
                        <span class="col-4 col-sm-3 text-end">
                            <input type="checkbox" class="form-check-input" id="toggleColumn_product" checked=""
                                onchange="checkColumn(this.checked)">
                        </span>
                    </label>
                    <!-- End Form Switch -->
                </div>
            </div>
        </div>
    </div>
</div>

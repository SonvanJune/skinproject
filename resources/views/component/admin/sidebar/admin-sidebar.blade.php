<!-- Sidebar -->
<div class="navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-bordered bg-white navbar-vertical-aside-initialized"
    style="margin-left: -18rem;">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">
            <!-- Logo -->

            <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="Front">
                <img class="navbar-brand-logo" src="{{ asset('images/logo.png') }}" alt="Logo"
                    data-hs-theme-appearance="default">
                <h2 class="logo-title text-primary m-0">{{ __('message.webName') }}</h2>
            </a>

            <!-- End Logo -->

            <!-- Navbar Vertical Toggle -->
            <button type="button" id="side-toggler" class=" navbar-aside-toggler" style="opacity: 1;">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-toggle="tooltip"
                    data-bs-placement="right" data-bs-original-title="Collapse"></i>
            </button>

            <!-- End Navbar Vertical Toggle -->

            <!-- Content -->
            <div class="navbar-vertical-content">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                    <!-- Dashboards -->
                    <div class="nav-item">
                        <a class="nav-link" href="{{ asset('admin') }}" data-placement="left">
                            <i class="bi-house-door nav-icon"></i>
                            <span class="nav-link-title">Dashboards</span>
                        </a>
                    </div>
                    <!-- End Dashboards -->

                    <!-- File Manager -->
                    <div class="nav-item">
                        <a class="nav-link" id="openFileManagerModal" href="#">
                            <i class="bi-folder2-open nav-icon"></i>
                            <span class="nav-link-title">File Manager</span>
                        </a>
                    </div>
                    <!-- End File Manager -->

                    <!-- Users -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesSubadminMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalMenuPagesSubadminMenu" aria-expanded="false"
                            aria-controls="navbarVerticalMenuPagesSubadminMenu">
                            <i class="bi-people nav-icon"></i>
                            <span class="nav-link-title">Subadmins</span>
                        </a>

                        <div id="navbarVerticalMenuPagesSubadminMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.subadmins') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.subadmins.create') }}">Add
                                Subadmin</a>
                        </div>
                    </div>
                    <!-- End Users -->

                    <!-- Questions -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesQuestionsMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalMenuPagesQuestionsMenu" aria-expanded="false"
                            aria-controls="navbarVerticalMenuPagesQuestionsMenu">
                            <i class="bi-question-circle nav-icon"></i>
                            <span class="nav-link-title">Security Questions</span>
                        </a>

                        <div id="navbarVerticalMenuPagesQuestionsMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.questions') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.questions.create') }}">Add
                                Security Question</a>
                        </div>
                    </div>
                    <!-- End Questions -->

                    <!-- Roles -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesRolesMenu"
                            role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPagesRolesMenu"
                            aria-expanded="false" aria-controls="navbarVerticalMenuPagesRolesMenu">
                            <i class="bi-person-exclamation nav-icon"></i>
                            <span class="nav-link-title">Roles</span>
                        </a>

                        <div id="navbarVerticalMenuPagesRolesMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.roles') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.roles.create') }}">Add
                                Role</a>
                        </div>
                    </div>
                    <!-- End Roles -->

                    <!-- Categories -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesCategoriesMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalMenuPagesCategoriesMenu" aria-expanded="false"
                            aria-controls="navbarVerticalMenuPagesCategoriesMenu">
                            <i class="bi-list-ul nav-icon"></i>
                            <span class="nav-link-title">Categories</span>
                        </a>

                        <div id="navbarVerticalMenuPagesCategoriesMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.categories') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.categories.create') }}">Add
                                Category</a>
                        </div>
                    </div>
                    <!-- End Categories -->

                    <!-- Products -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesProductsMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalMenuPagesProductsMenu" aria-expanded="false"
                            aria-controls="navbarVerticalMenuPagesProductsMenu">
                            <i class="bi-box-seam nav-icon"></i>
                            <span class="nav-link-title">Products</span>
                        </a>

                        <div id="navbarVerticalMenuPagesProductsMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.products') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.products.create') }}">Add
                                Product</a>
                        </div>
                    </div>
                    <!-- End Products -->

                    <!-- Slide Show -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalPostMenu" role="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarVerticalPostMenu" aria-expanded="false"
                            aria-controls="navbarVerticalPostMenu">
                            <i class="bi-grid-1x2 nav-icon"></i>
                            <span class="nav-link-title">Posts</span>
                        </a>

                        <div id="navbarVerticalPostMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.posts') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.posts.create') }}">Add Post</a>
                        </div>
                    </div>
                    <!-- End Products -->

                    <!-- Slide Show -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalSlideShowMenu"
                            role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalSlideShowMenu"
                            aria-expanded="false" aria-controls="navbarVerticalSlideShowMenu">
                            <i class="bi-images nav-icon"></i>
                            <span class="nav-link-title">SlideShows</span>
                        </a>

                        <div id="navbarVerticalSlideShowMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.slideshows') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.slideshows.create') }}">Add SlideShow</a>
                        </div>
                    </div>
                    <!-- End SlideShows -->

                    <!-- Coupons -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalSlideShowCouponMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalSlideShowCouponMenu" aria-expanded="false"
                            aria-controls="navbarVerticalSlideShowCouponMenu">
                            <i class="bi bi-ticket-perforated nav-icon"></i>
                            <span class="nav-link-title">Coupons</span>
                        </a>

                        <div id="navbarVerticalSlideShowCouponMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.coupons') }}">Table</a>
                            <a class="nav-link " href="{{ route('admin.coupons.create') }}">Add Coupon</a>
                        </div>
                    </div>
                    <!-- End Coupons -->

                    <!-- Tracking code -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalSlideShowTrackingMenu"
                            role="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarVerticalSlideShowTrackingMenu" aria-expanded="false"
                            aria-controls="navbarVerticalSlideShowTrackingMenu">
                            <i class="bi-code nav-icon"></i>
                            <span class="nav-link-title">Tracking Code</span>
                        </a>

                        @php
                            $canBeAdded = false;
                            if (isset($paginatedDTO) && isset($paginatedDTO->totalArr)) {
                                $canBeAdded = $paginatedDTO->totalArr !== 3;
                            }
                        @endphp

                        <div id="navbarVerticalSlideShowTrackingMenu" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.tracking-codes') }}">Table</a>
                            <a class="nav-link {{ $canBeAdded ? '' : 'disabled' }}"
                                href="{{ route('admin.tracking-codes.create') }}">Add Tracking Code</a>
                        </div>
                    </div>
                    <!-- End Revenues -->

                    <div class="nav-item">
                        <a class="nav-link " href="{{ route('admin.mails') }}" data-placement="left">
                            <i class="bi-envelope nav-icon"></i>
                            <span class="nav-link-title">Email</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link " href="{{ route('admin.payment.index') }}" data-placement="left">
                            <i class="bi-file-earmark-pdf nav-icon"></i>
                            <span class="nav-link-title">Payment Snapshots</span>
                        </a>
                    </div>

                    <!-- General Settings -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle collapsed" href="#navbarVerticalMenuPagesOther"
                            role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPagesOther"
                            aria-expanded="false" aria-controls="navbarVerticalMenuPagesOther">
                            <i class="bi-sliders nav-icon"></i>
                            <span class="nav-link-title">Other Settings</span>
                        </a>

                        <div id="navbarVerticalMenuPagesOther" class="nav-collapse collapse "
                            data-bs-parent="#navbarVerticalMenuPagesMenu" hs-parent-area="#navbarVerticalMenu">
                            <a class="nav-link " href="{{ route('admin.languages') }}">Languages</a>
                            <a class="nav-link " href="{{ route('admin.paypal.form') }}">Payment Information</a>
                            <a class="nav-link " href="{{ route('admin.maintenance.index') }}">Maintenance
                                Setting</a>
                            <a class="nav-link " href="{{ route('admin.defaultImages.index') }}">Default Images</a>
                            <a class="nav-link " href="{{ route('admin.vat.index') }}">Vat Settings</a>
                        </div>
                    </div>
                    <!-- End General Settings -->
                </div>

            </div>
            <!-- End Content -->
        </div>

    </div>
</div>
<!-- End Sidebar -->

@extends('layout')

@section('title', __('message.helpPageWebTitle'))

@push('css')
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
@endpush

@section('content')
    <div class="help-us">
        <div class="container py-4">
            <header class="d-flex align-items-center gap-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                <div>
                    <h1 class="h4 mb-1">{{__('message.helpPageTitle')}}</h1>
                    <p class="text-muted mb-0">{{__('message.helpPageSmallTitle')}}
                    </p>
                </div>
            </header>

            <div class="row gy-4">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="card p-4">
                        <h2 class="h5">{{__('message.helpPageListTitle')}}</h2>
                        <div class="d-grid gap-3">

                            <!-- Step 1 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-primary">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">1. {{__('message.helpPageSearchTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageSearchDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-info">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">2. {{__('message.helpPageProductDetailTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageProductDetailDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">3. {{__('message.helpPagePaymentTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPagePaymentDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-info">
                                    <i class="bi bi-envelope-check"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">4. {{__('message.helpPageEmailTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageEmailDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-primary">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">5. {{__('message.helpPageRegisterTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageRegisterDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 6 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-info">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">6. {{__('message.helpPageLoginTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageLoginDetail')}}
                                    </p>
                                </div>
                            </div>

                            <!-- Step 7 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-warning">
                                    <i class="bi bi-key"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">7. {{__('message.helpPageForgetPassTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageForgetPassTitle')}}</p>
                                </div>
                            </div>

                            <!-- Step 8 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-danger">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">8. {{__('message.helpPageForgetPass2Title')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageForgetPass2Detail')}}
                                    </p>
                                </div>
                            </div>

                            <!-- Step 9 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-purple">
                                    <i class="bi bi-question-circle"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">9. {{__('message.helpPageForgetQuestionTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageForgetQuestionDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 10 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-success">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">10. {{__('message.helpPageOrderListTitle')}}</h3>
                                    <p class="text-muted mb-0">{{__('message.helpPageOrderListDetail')}}</p>
                                </div>
                            </div>

                            <!-- Step 11 -->
                            <div class="d-flex gap-3 align-items-start">
                                <div class="icon fs-3 text-primary">
                                    <i class="bi bi-file-lock"></i>
                                </div>
                                <div>
                                    <h3 class="h6 mb-1">11. {{__('message.helpPageZipPassTitle')}}</h3>
                                    <p class="text-muted mb-0"><strong>{{__('message.helpPageZipPassDetail')}}</strong>
                                    </p>
                                </div>
                            </div>

                        </div>


                        <hr class="my-4">

                        {{-- <h2 class="h5">Ví dụ ảnh minh họa</h2>
                        <div class="row g-2">
                            <div class="col-6 col-md-6">
                                <div class="bg-light rounded p-2 border"><img
                                        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23e8f5ff'/><text x='50%' y='50%' font-size='20' text-anchor='middle' fill='%23525cf5' font-family='Arial' dy='.3em'>Ảnh sản phẩm đẹp - Góc 1</text></svg>"
                                        alt="Ảnh góc 1"></div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="bg-light rounded p-2 border"><img
                                        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23f0fff4'/><text x='50%' y='50%' font-size='20' text-anchor='middle' fill='%2306b6d4' font-family='Arial' dy='.3em'>Ảnh sản phẩm - Góc 2</text></svg>"
                                        alt="Ảnh góc 2"></div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="bg-light rounded p-2 border"><img
                                        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23fff7ed'/><text x='50%' y='50%' font-size='20' text-anchor='middle' fill='%23f97316' font-family='Arial' dy='.3em'>Ảnh chi tiết</text></svg>"
                                        alt="Ảnh chi tiết"></div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="bg-light rounded p-2 border"><img
                                        src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23f5f3ff'/><text x='50%' y='50%' font-size='20' text-anchor='middle' fill='%236b21a8' font-family='Arial' dy='.3em'>Ảnh hộp & phụ kiện</text></svg>"
                                        alt="Ảnh hộp"></div>
                            </div>
                        </div> --}}

                        <hr class="my-4">

                        {{-- <h2 class="h5">Câu hỏi thường gặp</h2>
                        <div class="mt-2">
                            <div class="border-top py-2">
                                <div class="d-flex justify-content-between faq-q" role="button">
                                    <strong>Làm sao để hủy đơn?</strong><span class="toggle">+</span>
                                </div>
                                <div class="faq-a">Bạn có thể hủy trong mục "Đơn hàng của tôi" nếu đơn chưa chuyển giao.
                                    Nếu đã gửi, liên hệ người bán để thỏa thuận hoặc chọn trả hàng khi nhận.</div>
                            </div>
                            <div class="border-top py-2">
                                <div class="d-flex justify-content-between faq-q" role="button">
                                    <strong>Phí vận chuyển tính thế nào?</strong><span class="toggle">+</span>
                                </div>
                                <div class="faq-a">Phí vận chuyển có thể do người bán hoặc người mua chịu theo thỏa thuận
                                    — thông tin này được hiển thị ngay trong tin sản phẩm.</div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card p-4">
                        <h4>{{__('message.helpPageMenuBar')}}</h4>
                        <div class="d-grid gap-2 mt-3 mb-4">
                            <button id="printBtn" class="btn btn-primary">{{__('message.helpPagePaste')}}</button>
                            <button id="contactBtn" class="btn btn-outline-secondary">{{__('message.helpPageContact')}}</button>
                        </div>

                        <hr>
                        <h5>{{__('message.helpPageContact')}}</h5>
                        <ul class="text-muted small ps-3 mb-0">
                            <li><i class="bi bi-telephone-fill me-1"></i>
                                <a href="tel:0123456789" class="text-decoration-none">{{__('message.webPhone')}}</a>
                            </li>
                            {{-- <li><i class="bi bi-facebook me-1"></i>
                                <a href="https://www.facebook.com/binhcaochinh" target="_blank"
                                    class="text-decoration-none">{{__('message.helpPageHelper')}}</a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>

            <footer class="text-center mt-4 text-muted small">
                {{__('message.helpPageFooter')}}
            </footer>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/helpUs.js') }}"></script>
@endpush

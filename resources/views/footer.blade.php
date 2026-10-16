<section class="footer">
    <div class="shape-mid">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="logo">
                        <a href="{{ url(App::getLocale() . '/') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="PPFTEMPLATE" loading="lazy">
                        </a>
                    </div>
                    <h6>{{ __('message.footerIntro') }}</h6>
                </div>
                <div class="col-md-3">
                    <div class="title">
                        <h4>{{ __('message.categories') }}</h4>
                    </div>
                    <div class="list">
                        <a href="{{ route('home') }}" class="item"><i
                                class="fa-solid fa-house"></i>{{ __('message.home') }}</a>
                        @isset($categories)
                            @foreach ($categories as $category)
                                <a href="{{ $category->url }}"
                                    class="item"><i
                                        class="{{ 'fa-solid fa-' . strtolower($category->name) }}"></i>{{ $category->name }}</a>
                            @endforeach
                        @endisset
                    </div>

                    <div class="title under">
                        <h4>{{ __('message.support') }}</h4>
                    </div>
                    <div class="list">
                        <a href="{{ route('blog') }}" class="item"><i
                                class="fa-brands fa-blogger-b"></i>{{ __('message.blog') }}</a>
                        <a href="{{ route('contact') }}" class="item"><i
                                class="fa-solid fa-handshake-angle"></i>{{ __('message.contactUs') }}</a>
                        <a href="{{ route('helps') }}" class="item"><i
                                class="fa-solid fa-circle-question"></i>{{ __('message.helpPage') }}</a>
                        <a href="{{ route('policies') }}" class="item"><i
                                class="fa-solid fa-file-contract"></i>{{ __('message.policyTitle') }}</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="title" style="margin-top: 70px">
                        <h4>{{ __('message.account') }}</h4>
                    </div>
                    <div class="list">
                        <a href="{{ route('account') }}" class="item"><i
                                class="fa-solid fa-user"></i>{{ __('message.myAccount') }}</a>
                        <a href="{{ route('cart') }}" class="item"><i
                                class="fa-solid fa-cart-shopping"></i>{{ __('message.cart') }}</a>
                        <a href="{{ route('order') }}" class="item"><i
                                class="fa-solid fa-list"></i>{{ __('message.orderHistory') }}</a>
                    </div>
                </div>
                <div class="col-md-3 contact">
                    <h4>{{ __('message.contactUs') }}</h4>
                    <div class="list-btn">
                        {{-- <a href="#" class="bt conta"><i
                                class="fa-brands fa-facebook"></i>{{ __('message.facebook') }}</a> --}}
                        <a href="tel:{{ __('message.webPhone') }}" class="bt whatapp"><i
                                class="fa-solid fa-phone"></i>{{ __('message.webPhone') }}</a>
                        {{-- <a href="" class="bt insta"><i class="fa-brands fa-instagram"></i>Instagram</a>
                        <a href="" class="bt youtube"><i class="fa-brands fa-youtube"></i>Youtube</a> --}}
                    </div>
                </div>
            </div>
            {{-- Trust Badges & Accepted Payments & 7-Day Guarantee --}}
            <div class="row mt-4 pt-4 footer-guarantee-row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="text-uppercase fw-bold footer-guarantee-title mb-2">
                        <i class="fa-solid fa-shield-halved me-2"></i>{{ __('message.guaranteeTitle') }}
                    </h5>
                    <p class="small footer-guarantee-text mb-0">
                        {{ __('message.guaranteeDesc') }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="text-uppercase fw-bold footer-payments-title mb-2">{{ __('message.paymentMethods') }}</h6>
                    <div class="d-flex flex-wrap justify-content-md-end gap-2 footer-payments-list">
                        <span class="badge footer-payment-badge brand-paypal px-2 py-1"><i class="fa-brands fa-paypal me-1"></i> PayPal</span>
                        <span class="badge footer-payment-badge brand-stripe px-2 py-1"><i class="fa-brands fa-stripe me-1"></i> Stripe</span>
                        <span class="badge footer-payment-badge brand-ssl px-2 py-1"><i class="fa-solid fa-lock me-1"></i> {{ __('message.sslEncryptedBadge') }}</span>
                        <span class="badge footer-payment-badge px-2 py-1"><i class="fa-brands fa-bitcoin me-1"></i> BTC</span>
                        <span class="badge footer-payment-badge px-2 py-1"><i class="fa-brands fa-ethereum me-1"></i> ETH</span>
                        <span class="badge footer-payment-badge px-2 py-1">USDT</span>
                        <span class="badge footer-payment-badge px-2 py-1">SOL</span>
                        <span class="badge footer-payment-badge px-2 py-1">XRP</span>
                        <span class="badge footer-payment-badge px-2 py-1">DOGE</span>
                        <span class="badge footer-payment-badge px-2 py-1">Pi Network</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (session('need_security') != null)
        @include('component.security-modal.index', [
            'title' => session('need_security'),
        ])
    @endif

    @include('component.noticationNoData.notification.index', [
        'notification' => 'security_success',
        'type' => 'success',
    ])
    @include('component.noticationNoData.notification.index', [
        'notification' => 'security_error',
        'type' => 'error',
    ])
</section>


{{-- copy right --}}
<div class="copy-right">{{ __('message.webName') }} &copy; {{ date('Y') }}</div>


@push('js')
    <script src="{{ asset('js/alert.js') }}"></script>
@endpush

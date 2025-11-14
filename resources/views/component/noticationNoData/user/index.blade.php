<div class="container d-flex flex-column justify-content-center align-items-center vh-30 user-noti">
    <div class="text-center">
        <img src="{{asset('images/notlogin.jpg')}}" alt="Please Login" class="img-fluid mb-4">
        <h1 class="h4 mb-3">{{ __('message.needToLogin') }}</h1>
        <a href="{{ url(App::getLocale() . '/login') }}" class="btn btn-primary btn-lg">{{ __('message.goToLogin') }}</a>
    </div>
</div>
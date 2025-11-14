<div class="sidebar">
    <div class="user">
        <img src="{{ asset('images/profile.png') }}"
            alt="" srcset="">
        <h4>{{ $user->user_first_name . ' ' . $user->user_last_name }} </h4>
    </div>
    <a class="item" href="{{ url(App::getLocale() . '/account') }}">{{ __('message.personalInfor') }}<i class="fa-solid fa-user"></i></a>
    <a class="item" href="{{ url(App::getLocale() . '/account/change-password') }}">{{ __('message.changePassword') }}<i class="fa-solid fa-key"></i></a>
    <a class="item" href="{{ url(App::getLocale() . '/account/change-password-level-2') }}">{{ __('message.changePasswordLevel2') }}<i class="fa-solid fa-key"></i></a>
    <a class="item" href="{{ url(App::getLocale() . '/account/change-security-questions') }}">{{ __('message.changeSecurityQuestion') }}<i class="fa-solid fa-key"></i></a>
    <a class="item" href="{{ route('order') }}">{{ __('message.orderHistory') }}<i
            class="fa-solid fa-clock-rotate-left"></i></a>
</div>
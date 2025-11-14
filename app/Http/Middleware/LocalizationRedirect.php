<?php

namespace App\Http\Middleware;

use Closure;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LocalizationRedirect
{
    public function handle($request, Closure $next)
    {
        // Nếu URL không có tiền tố ngôn ngữ, chuyển hướng đến ngôn ngữ mặc định
        $locale = LaravelLocalization::getCurrentLocale();
        if (!$locale) {
            return redirect()->to(LaravelLocalization::getLocalizedURL(config('app.locale')));
        }

        return $next($request);
    }
}

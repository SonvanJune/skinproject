<?php

namespace App\Http\Middleware;

use App\Models\UrlRedirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class RedirectLegacyUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethodSafe()) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');

        try {
            if (Schema::hasTable('url_redirects')) {
                $redirect = Cache::remember("redirect_path_{$path}", 86400, function () use ($path) {
                    return UrlRedirect::where('source_path', $path)
                        ->where('is_active', true)
                        ->first();
                });

                if ($redirect) {
                    try {
                        UrlRedirect::where('id', $redirect->id)->increment('hits', 1, [
                            'last_accessed_at' => now(),
                        ]);
                    } catch (\Throwable $e) {}

                    $queryString = $request->getQueryString();
                    $targetUrl = $redirect->target_path . ($queryString ? '?' . $queryString : '');

                    return redirect($targetUrl, (int) $redirect->status_code);
                }
            }
        } catch (\Throwable $e) {
            // Graceful fallback if DB or cache not ready
        }

        // Automatic 301 redirect for legacy category URLs: /product-category/... -> /c/...
        if (preg_match('#^/((?:[a-z]{2}(?:-[A-Za-z]+)?/)?)(?:product-category)/(.*)$#', $path, $matches)) {
            $prefix = !empty($matches[1]) ? '/' . rtrim($matches[1], '/') : '';
            $subPath = $matches[2];
            $queryString = $request->getQueryString();
            return redirect($prefix . '/c/' . $subPath . ($queryString ? '?' . $queryString : ''), 301);
        }

        // Automatic 301 redirect for legacy product URLs: /product/... -> /p/...
        if (preg_match('#^/((?:[a-z]{2}(?:-[A-Za-z]+)?/)?)(?:product)/(.*)$#', $path, $matches)) {
            $prefix = !empty($matches[1]) ? '/' . rtrim($matches[1], '/') : '';
            $subPath = $matches[2];
            $queryString = $request->getQueryString();
            return redirect($prefix . '/p/' . $subPath . ($queryString ? '?' . $queryString : ''), 301);
        }

        // Automatic 301 redirect for legacy brand URLs: /brand/... -> /b/...
        if (preg_match('#^/((?:[a-z]{2}(?:-[A-Za-z]+)?/)?)(?:brand)/(.*)$#', $path, $matches)) {
            $prefix = !empty($matches[1]) ? '/' . rtrim($matches[1], '/') : '';
            $subPath = $matches[2];
            $queryString = $request->getQueryString();
            return redirect($prefix . '/b/' . $subPath . ($queryString ? '?' . $queryString : ''), 301);
        }

        return $next($request);
    }
}

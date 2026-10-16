<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    protected array $supportedLocales = ['en', 'vi', 'ja', 'es', 'fr', 'zh'];

    protected array $hreflangMap = [
        'en' => 'en',
        'vi' => 'vi',
        'ja' => 'ja',
        'es' => 'es',
        'fr' => 'fr',
        'zh' => 'zh-Hans',
    ];

    /**
     * Render the master sitemap index containing links to all localized sitemaps.
     */
    public function index(): Response
    {
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $lastMod = now()->toIso8601String();

        foreach ($this->supportedLocales as $locale) {
            $loc = "{$baseUrl}/sitemap-{$locale}.xml";
            $xml .= "    <sitemap>\n";
            $xml .= "        <loc>{$loc}</loc>\n";
            $xml .= "        <lastmod>{$lastMod}</lastmod>\n";
            $xml .= "    </sitemap>\n";
        }

        $xml .= '</sitemapindex>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Render a localized XML sitemap with xhtml:link hreflang alternates.
     */
    public function localeSitemap(Request $request, string $locale): Response
    {
        if (!in_array($locale, $this->supportedLocales)) {
            abort(404, 'Locale not found');
        }

        $baseUrl = rtrim(url('/'), '/');

        // 1. Static Pages
        $staticRoutes = [
            '' => ['priority' => '1.0', 'changefreq' => 'daily'],
            'sales' => ['priority' => '0.8', 'changefreq' => 'daily'],
            'new-products' => ['priority' => '0.8', 'changefreq' => 'daily'],
            'popular-products' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            'blog' => ['priority' => '0.7', 'changefreq' => 'weekly'],
            'contact-us' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            'helps' => ['priority' => '0.5', 'changefreq' => 'monthly'],
            'policies' => ['priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        // 2. Active Categories & Brands
        $categories = Category::where('category_status', Category::CATEGORY_STATUS_ACTIVE)
            ->whereNotNull('category_slug')
            ->select('category_id', 'category_slug', 'category_type', 'parent_id', 'updated_at')
            ->get();

        // 3. Active Products (via posts)
        $products = Post::where('post_status', 1)
            ->where('post_type', 1) // 1 = Product
            ->whereNotNull('post_slug')
            ->select('post_id', 'post_slug', 'updated_at')
            ->get();

        // 4. Active Blogs
        $blogs = Post::where('post_status', 1)
            ->where('post_type', 2) // 2 = Blog/Post
            ->whereNotNull('post_slug')
            ->select('post_id', 'post_slug', 'updated_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        // Render Static Pages
        foreach ($staticRoutes as $route => $meta) {
            $pathSuffix = $route ? "/{$route}" : '';
            $xml .= $this->buildUrlXml(
                $baseUrl,
                $locale,
                $pathSuffix,
                $meta['priority'],
                $meta['changefreq'],
                now()->toIso8601String()
            );
        }

        // Render Categories & Brands
        foreach ($categories as $cat) {
            $catPath = $cat->category_slug;
            $prefix = ($cat->category_type == Category::CATEGORY_TYPE_BRAND) ? '/b/' : '/c/';
            $pathSuffix = "{$prefix}{$catPath}";
            $lastMod = $cat->updated_at ? $cat->updated_at->toIso8601String() : now()->toIso8601String();
            $xml .= $this->buildUrlXml(
                $baseUrl,
                $locale,
                $pathSuffix,
                '0.8',
                'weekly',
                $lastMod
            );
        }

        // Render Products
        foreach ($products as $prod) {
            $pathSuffix = "/p/{$prod->post_slug}";
            $lastMod = $prod->updated_at ? $prod->updated_at->toIso8601String() : now()->toIso8601String();
            $xml .= $this->buildUrlXml(
                $baseUrl,
                $locale,
                $pathSuffix,
                '0.9',
                'weekly',
                $lastMod
            );
        }

        // Render Blogs
        foreach ($blogs as $blog) {
            $pathSuffix = "/blog/{$blog->post_slug}";
            $lastMod = $blog->updated_at ? $blog->updated_at->toIso8601String() : now()->toIso8601String();
            $xml .= $this->buildUrlXml(
                $baseUrl,
                $locale,
                $pathSuffix,
                '0.7',
                'monthly',
                $lastMod
            );
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Build <url> node with multilingual xhtml:link alternates.
     */
    protected function buildUrlXml(
        string $baseUrl,
        string $currentLocale,
        string $pathSuffix,
        string $priority,
        string $changefreq,
        string $lastMod
    ): string {
        $loc = "{$baseUrl}/{$currentLocale}{$pathSuffix}";

        $out = "  <url>\n";
        $out .= "    <loc>{$loc}</loc>\n";

        // xhtml:link for every supported locale
        foreach ($this->supportedLocales as $locCode) {
            $hreflang = $this->hreflangMap[$locCode] ?? $locCode;
            $altUrl = "{$baseUrl}/{$locCode}{$pathSuffix}";
            $out .= "    <xhtml:link rel=\"alternate\" hreflang=\"{$hreflang}\" href=\"{$altUrl}\"/>\n";
        }

        // x-default points to English
        $defaultUrl = "{$baseUrl}/en{$pathSuffix}";
        $out .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"{$defaultUrl}\"/>\n";

        $out .= "    <lastmod>{$lastMod}</lastmod>\n";
        $out .= "    <changefreq>{$changefreq}</changefreq>\n";
        $out .= "    <priority>{$priority}</priority>\n";
        $out .= "  </url>\n";

        return $out;
    }
}

@php
    $schemas = [];
    $type = $type ?? null;

    // Helper closure to safely convert any value (string, array, null) to a clean string
    $safeString = function ($value) {
        if (is_null($value)) {
            return '';
        }
        if (is_array($value)) {
            return implode(' ', array_filter(array_map('strval', $value)));
        }
        return (string) $value;
    };

    // 1. Custom / Direct schema data (array or collection)
    if (!empty($data)) {
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            foreach ($data as $item) {
                $schemas[] = $item;
            }
        } else {
            $schemas[] = $data;
        }
    } elseif (!empty($schema)) {
        if (is_array($schema) && isset($schema[0]) && is_array($schema[0])) {
            foreach ($schema as $item) {
                $schemas[] = $item;
            }
        } else {
            $schemas[] = $schema;
        }
    }

    // 2. Global Organization & WebSite schema
    if ($type === 'global' || $type === 'organization' || $type === 'website') {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => 'https://ppftemplate.com/#organization',
                    'name' => 'PPFTEMPLATE',
                    'url' => 'https://ppftemplate.com',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo.png'),
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'customer service',
                        'email' => 'support@ppftemplate.com',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => 'https://ppftemplate.com/#website',
                    'url' => 'https://ppftemplate.com',
                    'name' => 'PPFTEMPLATE',
                    'publisher' => [
                        '@id' => 'https://ppftemplate.com/#organization',
                    ],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => 'https://ppftemplate.com/' . app()->getLocale() . '/search?q={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
    }

    // 3. Product Schema (Only when explicitly requested with type='product')
    if ($type === 'product') {
        $prodName = is_object($product ?? null) ? ($product->product_name ?? '') : ($name ?? '');
        $prodSku = is_object($product ?? null) ? ($product->product_slug ?? '') : ($sku ?? '');
        $prodPrice = $price ?? (is_object($product ?? null) ? ($productPrice ?? $product->price ?? '') : '');
        $prodImage = !empty($image) ? (is_array($image) ? $image : [$image]) : (!empty($mainImage) ? [$mainImage] : []);
        $prodDesc = $safeString($description ?? $cleanDesc ?? '');
        $prodUrl = $url ?? url()->current();

        $schemas[] = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $safeString($prodName),
            'image' => $prodImage,
            'description' => $prodDesc,
            'sku' => $safeString($prodSku),
            'offers' => [
                '@type' => 'Offer',
                'url' => (string) $prodUrl,
                'priceCurrency' => $currency ?? 'USD',
                'price' => $safeString($prodPrice),
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => 'https://schema.org/InStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $safeString($seller ?? 'PPFTEMPLATE'),
                ],
            ],
        ];
    }

    // 4. Article / BlogPosting Schema (Only when explicitly requested)
    if ($type === 'article' || $type === 'blog' || $type === 'blogPosting') {
        $articleHeadline = is_object($post ?? null) ? ($post->name ?? '') : ($headline ?? $name ?? '');
        $articleImage = !empty($image) ? (is_array($image) ? $image : [$image]) : (!empty($postImage) ? [$postImage] : [asset('images/logo.png')]);

        $pubDate = date('c');
        if (isset($post->release) && is_object($post->release) && method_exists($post->release, 'toIso8601String')) {
            $pubDate = $post->release->toIso8601String();
        } elseif (!empty($datePublished)) {
            $pubDate = is_string($datePublished) ? $datePublished : (method_exists($datePublished, 'toIso8601String') ? $datePublished->toIso8601String() : date('c'));
        }

        $modDate = date('c');
        if (isset($post->updated_at) && is_object($post->updated_at) && method_exists($post->updated_at, 'toIso8601String')) {
            $modDate = $post->updated_at->toIso8601String();
        } elseif (!empty($dateModified)) {
            $modDate = is_string($dateModified) ? $dateModified : (method_exists($dateModified, 'toIso8601String') ? $dateModified->toIso8601String() : date('c'));
        }

        $authorName = is_object($post ?? null) ? ($post->author ?? 'PPFTEMPLATE Team') : ($author ?? 'PPFTEMPLATE Team');
        $articleDesc = $safeString($description ?? $cleanDesc ?? '');

        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $safeString($articleHeadline),
            'image' => $articleImage,
            'datePublished' => $pubDate,
            'dateModified' => $modDate,
            'author' => [
                '@type' => 'Person',
                'name' => $safeString($authorName),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'PPFTEMPLATE',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'description' => $articleDesc,
        ];
    }

    // 5. BreadcrumbList Schema (Rendered if type === 'breadcrumb' OR if $breadcrumbs is explicitly provided)
    if (!empty($breadcrumbs) && is_array($breadcrumbs)) {
        $itemList = [];
        foreach ($breadcrumbs as $idx => $crumb) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $idx + 1,
                'name' => $safeString($crumb['name'] ?? ''),
                'item' => (string) ($crumb['url'] ?? $crumb['item'] ?? ''),
            ];
        }

        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList,
        ];
    }
@endphp

@foreach($schemas as $schemaItem)
<script type="application/ld+json">
{!! json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endforeach

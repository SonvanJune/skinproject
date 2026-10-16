<?php

namespace Database\Seeders;

use App\Models\UrlRedirect;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UrlRedirectLegacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Schema::hasTable('url_redirects')) {
            return;
        }

        $locales = ['en', 'vi', 'ja', 'es', 'fr', 'zh'];

        $mappings = [
            // Typo / Old slug mappings
            ['from' => 'product-category/audi1', 'to' => 'c/car/audi/exterior'],
            ['from' => 'product-category/sgsff', 'to' => 'c/wrap-stock/3m-vinyl'],
            ['from' => 'brand/car-pdf-interrior-template', 'to' => 'b/car-pdf-interior-template'],
            [
                'from' => 'product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive',
                'to' => 'p/mini-cooper-hardtop-2023-interior-rhd'
            ],
            // Brand routes standardization (/brand/... -> /b/...)
            ['from' => 'brand/wrap-pdf-template', 'to' => 'b/wrap-pdf-template'],
            ['from' => 'brand/car-pdf-interior-template', 'to' => 'b/car-pdf-interior-template'],
            ['from' => 'brand/car-pdf-outside-template', 'to' => 'b/car-pdf-outside-template'],
            ['from' => 'brand/car-pdf-screen-template', 'to' => 'b/car-pdf-screen-template'],
            ['from' => 'brand/door-handle-pdf-template', 'to' => 'b/door-handle-pdf-template'],
        ];

        foreach ($mappings as $map) {
            // Unprefixed (fallback to English)
            UrlRedirect::updateOrCreate(
                ['source_path' => '/' . $map['from']],
                [
                    'target_path' => '/en/' . $map['to'],
                    'status_code' => 301,
                    'is_active' => true,
                ]
            );

            // Prefixed with each supported locale
            foreach ($locales as $locale) {
                UrlRedirect::updateOrCreate(
                    ['source_path' => '/' . $locale . '/' . $map['from']],
                    [
                        'target_path' => '/' . $locale . '/' . $map['to'],
                        'status_code' => 301,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}

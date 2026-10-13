<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get or fallback to first user
        $user = User::first();
        if (!$user) {
            $userId = Str::uuid()->toString();
            DB::table('users')->insert([
                'user_id' => $userId,
                'user_first_name' => 'Admin',
                'user_last_name' => 'System',
                'user_email' => 'admin@ppftemplate.com',
                'user_password' => bcrypt('Admin@123456'),
                'user_status' => 1,
                'user_phone' => '0123456789',
                'user_birthday' => '1995-01-01',
                'user_avatar' => 'images/avatars/default_avatar.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user = User::first();
        }

        // Detailed product catalog mapped by category slug
        $categoryProducts = [
            // --- MOBILE BRANDS ---
            'iphone' => [
                [
                    'name' => 'iPhone 16 Pro Max - Full Body Skin Template',
                    'price' => 29.99,
                    'image' => 'file-manager/categories/iphone.png',
                    'desc' => '<p>Ultra-accurate precision cut template for <strong>Apple iPhone 16 Pro Max</strong>. Includes full back, camera bump, edge wraps, and separated lens protectors. Tested on real devices for Graphtec, Roland, and Cricut vinyl cutters.</p>',
                ],
                [
                    'name' => 'iPhone 15 Pro - Full Body & Frame Skin Cut File',
                    'price' => 24.99,
                    'image' => 'file-manager/categories/iphone.png',
                    'desc' => '<p>Precision cut files for <strong>iPhone 15 Pro</strong> with Action Button cutouts. Formats included: SVG, DXF, CDR, EPS, PLT.</p>',
                ],
                [
                    'name' => 'iPhone 14 Pro Max - Vector Cut Template',
                    'price' => 19.99,
                    'image' => 'file-manager/categories/iphone.png',
                    'desc' => '<p>Accurate vector wrap template for <strong>iPhone 14 Pro Max</strong>. Fits standard vinyl and 3M Di-Noc / 1080 materials.</p>',
                ],
            ],

            'samsung' => [
                [
                    'name' => 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template',
                    'price' => 29.99,
                    'image' => 'file-manager/categories/samsung.png',
                    'desc' => '<p>Full body vector cut template for <strong>Samsung Galaxy S24 Ultra</strong> with S-Pen cutout and individual camera lens protection.</p>',
                ],
                [
                    'name' => 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template',
                    'price' => 34.99,
                    'image' => 'file-manager/categories/samsung.png',
                    'desc' => '<p>Comprehensive skin template for <strong>Samsung Galaxy Z Fold 5</strong> covering hinge, outer screen bezel, and rear chassis.</p>',
                ],
                [
                    'name' => 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File',
                    'price' => 22.99,
                    'image' => 'file-manager/categories/samsung.png',
                    'desc' => '<p>Ready-to-cut vector file for <strong>Samsung Galaxy S23 Plus</strong>. Flawless 1:1 scale dimensions.</p>',
                ],
            ],

            'google' => [
                [
                    'name' => 'Google Pixel 9 Pro - Camera Visor & Back Cut File',
                    'price' => 26.99,
                    'image' => 'file-manager/categories/google.png',
                    'desc' => '<p>Precision vector files for <strong>Google Pixel 9 Pro</strong> with the newly redesigned camera island and chamfered edges.</p>',
                ],
                [
                    'name' => 'Google Pixel 8 Pro - Full Body Vinyl Skin Template',
                    'price' => 21.99,
                    'image' => 'file-manager/categories/google.png',
                    'desc' => '<p>Tested cutting template for <strong>Google Pixel 8 Pro</strong>. 100% accurate alignment on original device.</p>',
                ],
            ],

            'oppo' => [
                [
                    'name' => 'Oppo Find X7 Ultra - Quad Camera Skin Template',
                    'price' => 25.99,
                    'image' => 'file-manager/categories/oppo.png',
                    'desc' => '<p>Precision cut vector file for <strong>Oppo Find X7 Ultra</strong> circular camera module and curved back panel.</p>',
                ],
                [
                    'name' => 'Oppo Reno 11 Pro - Full Wrap Cut File',
                    'price' => 19.99,
                    'image' => 'file-manager/categories/oppo.png',
                    'desc' => '<p>Accurate skin cut file for <strong>Oppo Reno 11 Pro</strong>. Includes back and antenna split lines.</p>',
                ],
            ],

            'sony' => [
                [
                    'name' => 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File',
                    'price' => 27.99,
                    'image' => 'file-manager/categories/sony.png',
                    'desc' => '<p>Accurate vector file for <strong>Sony Xperia 1 VI</strong> textured frame and triple Zeiss optics.</p>',
                ],
                [
                    'name' => 'Sony Xperia 5 V - Compact Vector Template',
                    'price' => 22.99,
                    'image' => 'file-manager/categories/sony.png',
                    'desc' => '<p>Precision wrap cut file for <strong>Sony Xperia 5 V</strong>. CDR, DXF, and SVG included.</p>',
                ],
            ],

            'lg' => [
                [
                    'name' => 'LG Velvet 5G - Raindrop Camera Skin Template',
                    'price' => 18.99,
                    'image' => 'file-manager/categories/lg.png',
                    'desc' => '<p>Precision skin template for <strong>LG Velvet 5G</strong> raindrop camera styling and back curve.</p>',
                ],
                [
                    'name' => 'LG Wing - Dual Swivel Screen Wrap Template',
                    'price' => 24.99,
                    'image' => 'file-manager/categories/lg.png',
                    'desc' => '<p>Unique dual-display cut template for <strong>LG Wing</strong> swivel mechanism.</p>',
                ],
            ],

            // --- LAPTOP BRANDS ---
            'alienware' => [
                [
                    'name' => 'Alienware m18 R2 - Top Lid & Palmrest Skin Template',
                    'price' => 39.99,
                    'image' => 'file-manager/categories/alienware.png',
                    'desc' => '<p>High-precision vinyl cut file for <strong>Alienware m18 R2</strong> gaming laptop. Covers top lid alien logo, keyboard deck, and trackpad.</p>',
                ],
                [
                    'name' => 'Alienware x16 - Slim Gaming Laptop Wrap File',
                    'price' => 36.99,
                    'image' => 'file-manager/categories/alienware.png',
                    'desc' => '<p>Full wrap template for <strong>Alienware x16</strong> with rear stadium lighting cutouts.</p>',
                ],
            ],

            'hp' => [
                [
                    'name' => 'HP Spectre x360 14 - Full Body Skin Template',
                    'price' => 32.99,
                    'image' => 'file-manager/categories/hp.png',
                    'desc' => '<p>Precision template for <strong>HP Spectre x360 14</strong> gem-cut corners, lid, and bottom vent cutouts.</p>',
                ],
                [
                    'name' => 'HP Omen 16 - Top Lid & Keyboard Deck Cut File',
                    'price' => 29.99,
                    'image' => 'file-manager/categories/hp.png',
                    'desc' => '<p>Vector cut file for <strong>HP Omen 16</strong> gaming laptop. 1:1 real-device verified.</p>',
                ],
            ],

            'acer' => [
                [
                    'name' => 'Acer Predator Helios 16 - Vinyl Skin Cut File',
                    'price' => 34.99,
                    'image' => 'file-manager/categories/acer.png',
                    'desc' => '<p>Custom skin template for <strong>Acer Predator Helios 16</strong> RGB exhaust and lid badge.</p>',
                ],
                [
                    'name' => 'Acer Swift Go 14 - Slim Ultrabook Skin Template',
                    'price' => 26.99,
                    'image' => 'file-manager/categories/acer.png',
                    'desc' => '<p>Minimalist precision template for <strong>Acer Swift Go 14</strong> OLED laptop.</p>',
                ],
            ],

            'asus' => [
                [
                    'name' => 'Asus ROG Zephyrus G16 - Full Body Skin Template',
                    'price' => 38.99,
                    'image' => 'file-manager/categories/asus.png',
                    'desc' => '<p>Precision cut file for <strong>Asus ROG Zephyrus G16</strong> slash lighting lid and stealth keyboard deck.</p>',
                ],
                [
                    'name' => 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template',
                    'price' => 28.99,
                    'image' => 'file-manager/categories/asus.png',
                    'desc' => '<p>Laser-measured cut file for <strong>Asus Zenbook 14 OLED</strong> monogram lid.</p>',
                ],
            ],

            'dell' => [
                [
                    'name' => 'Dell XPS 15 (9530) - Full Coverage Skin Template',
                    'price' => 35.99,
                    'image' => 'file-manager/categories/dell.png',
                    'desc' => '<p>Industry-standard skin template for <strong>Dell XPS 15</strong> carbon fiber palmrest, top lid, and bottom panel.</p>',
                ],
                [
                    'name' => 'Dell XPS 13 Plus - Seamless Touch Bar Skin File',
                    'price' => 31.99,
                    'image' => 'file-manager/categories/dell.png',
                    'desc' => '<p>Ultra-accurate template for <strong>Dell XPS 13 Plus</strong> zero-lattice keyboard and invisible glass trackpad.</p>',
                ],
            ],

            'lenovo' => [
                [
                    'name' => 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template',
                    'price' => 37.99,
                    'image' => 'file-manager/categories/lenovo.png',
                    'desc' => '<p>Engineered cut template for <strong>Lenovo Legion Pro 7i</strong> lid, palmrest, and vented base.</p>',
                ],
                [
                    'name' => 'ThinkPad X1 Carbon Gen 12 - Precision Cut File',
                    'price' => 32.99,
                    'image' => 'file-manager/categories/lenovo.png',
                    'desc' => '<p>Iconic TrackPoint-compatible cut file for <strong>ThinkPad X1 Carbon Gen 12</strong>.</p>',
                ],
            ],

            // --- CAR & PPF TEMPLATES ---
            'car-pdf-outside-template' => [
                [
                    'name' => 'Porsche 911 GT3 RS - Full Exterior PPF Cut File',
                    'price' => 149.99,
                    'image' => 'file-manager/slides/porsche-911-gt3-rs.jpg',
                    'desc' => '<p>Full vehicle exterior Paint Protection Film (PPF) pre-cut package for <strong>Porsche 911 GT3 RS</strong>. Includes front bumper, aero fenders, bonnet with nostrils, swan-neck wing, doors, and rear quarter panels. Pre-tested on Graphtec FC9000 & Roland cutters.</p>',
                ],
                [
                    'name' => 'Ferrari F8 Tributo - Complete Body PPF Template',
                    'price' => 169.99,
                    'image' => 'file-manager/slides/ferrari-f8-tributo.jpg',
                    'desc' => '<p>High-precision digital PPF cutting patterns for <strong>Ferrari F8 Tributo</strong>. Zero-stretch design for flawless film installation without blade-on-paint risks.</p>',
                ],
                [
                    'name' => 'Lamborghini Aventador - Paint Protection Film Cut File',
                    'price' => 179.99,
                    'image' => 'file-manager/slides/lamborghini-aventador.jpg',
                    'desc' => '<p>Extensive PPF cut package for <strong>Lamborghini Aventador LP780-4 Ultimae</strong> sharp edges, aggressive splitters, and engine louvers.</p>',
                ],
                [
                    'name' => 'Mercedes-AMG GT R - Full Body Wrap & PPF Template',
                    'price' => 139.99,
                    'image' => 'file-manager/slides/mercedes-amg-gt-r.jpg',
                    'desc' => '<p>Computer-engineered PPF cut file for <strong>Mercedes-AMG GT R</strong> Panamericana grille surrounds, long hood, and wide rear fenders.</p>',
                ],
            ],

            'car-pdf-interrior-template' => [
                [
                    'name' => 'Porsche 911 (992) Center Console & Dashboard PPF Template',
                    'price' => 49.99,
                    'image' => 'file-manager/categories/car-interrior.png',
                    'desc' => '<p>Interior anti-scratch protective film cut file for <strong>Porsche 911 (992)</strong> gloss piano black trims, gear toggle, and cup holder bezel.</p>',
                ],
                [
                    'name' => 'Mercedes-AMG Interior Carbon & Screen Trim Protection File',
                    'price' => 54.99,
                    'image' => 'file-manager/categories/car-interrior.png',
                    'desc' => '<p>High-wear area interior protection templates for <strong>Mercedes-AMG</strong> steering wheel accents and central console.</p>',
                ],
            ],

            'car-pdf-screen-template' => [
                [
                    'name' => 'Tesla Model 3 / Y Center Screen 15.4" Protector Template',
                    'price' => 19.99,
                    'image' => 'file-manager/categories/car-screen.png',
                    'desc' => '<p>Matte anti-glare & ultra-clear digital cutting templates for <strong>Tesla Model 3 & Model Y</strong> 15.4-inch navigation touchscreens.</p>',
                ],
                [
                    'name' => 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File',
                    'price' => 39.99,
                    'image' => 'file-manager/categories/car-screen.png',
                    'desc' => '<p>Pillar-to-pillar glass protection pattern for <strong>Mercedes EQS / EQE Hyperscreen</strong> curved OLED panels.</p>',
                ],
            ],

            'door-handle-pdf-template' => [
                [
                    'name' => 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit',
                    'price' => 14.99,
                    'image' => 'file-manager/categories/car-door-handle.png',
                    'desc' => '<p>Set of 12 universal contoured door handle cup & handle outer templates for cars, SUVs, and trucks. Defends against ring and nail scratches.</p>',
                ],
            ],

            // --- MOTORCYCLE TEMPLATES ---
            'wrap-pdf-template' => [
                [
                    'name' => 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File',
                    'price' => 69.99,
                    'image' => 'file-manager/categories/wrap-pdf.png',
                    'desc' => '<p>Complete digital livery and PPF template for <strong>Yamaha YZF-R1</strong> supersport bike. Includes tank, nose, side fairings, and tail cowl.</p>',
                ],
                [
                    'name' => 'Ducati Panigale V4 - Full Livery & Armor PPF Template',
                    'price' => 79.99,
                    'image' => 'file-manager/categories/motorcycle.png',
                    'desc' => '<p>Aero winglet and sculpted tank protection wrap file for <strong>Ducati Panigale V4 / V4S</strong>.</p>',
                ],
                [
                    'name' => 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package',
                    'price' => 64.99,
                    'image' => 'file-manager/categories/wrap-pdf.png',
                    'desc' => '<p>Ready-to-cut vector template for <strong>Kawasaki Ninja ZX-10R</strong> fairing graphics and stone chip defense.</p>',
                ],
            ],

            // --- WRAP STOCK ---
            'wrap-stock' => [
                [
                    'name' => 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack',
                    'price' => 45.99,
                    'image' => 'file-manager/categories/wrap-stock.png',
                    'desc' => '<p>Seamless vector twill weave carbon fiber textures optimized for large format printing and vinyl wrapping.</p>',
                ],
                [
                    'name' => 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern',
                    'price' => 59.99,
                    'image' => 'file-manager/categories/wrap-stock.png',
                    'desc' => '<p>Scalable vector camouflage graphics package for full automotive color-change wraps. 100% vector, infinite resolution.</p>',
                ],
                [
                    'name' => 'Liquid Bronze & Brushed Titanium Textured Wrap Vector',
                    'price' => 39.99,
                    'image' => 'file-manager/categories/wrap-stock.png',
                    'desc' => '<p>Luxury metallic bronze texture overlay template for automotive and consumer tech wraps.</p>',
                ],
            ],
        ];

        // Also make sure parent categories have representative products
        $allCategories = Category::all()->keyBy('category_slug');

        $insertedCount = 0;

        foreach ($categoryProducts as $categorySlug => $products) {
            $category = $allCategories->get($categorySlug);
            if (!$category) {
                continue;
            }

            // Determine parent category if present
            $parentCategory = $category->parent_id ? Category::find($category->parent_id) : null;

            foreach ($products as $prodData) {
                $productName = $prodData['name'];
                $slug = Str::slug($productName);

                // Avoid duplicate slug
                if (Post::where('post_slug', $slug)->exists()) {
                    continue;
                }

                $postId = Str::uuid()->toString();
                $productId = Str::uuid()->toString();
                $imagePath = $prodData['image'];

                // 1. Create Post
                $post = Post::create([
                    'post_id' => $postId,
                    'post_name' => $productName,
                    'post_slug' => $slug,
                    'post_release' => now()->subDays(rand(1, 45)),
                    'post_status' => Post::STATUS_RELEASE,
                    'post_type' => Post::TYPE_PRODUCT,
                    'user_id' => $user->user_id,
                    'post_content' => $prodData['desc'],
                    'post_image_path' => $imagePath,
                    'post_image_alt' => $productName,
                ]);

                // 2. Create Product
                $product = Product::create([
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'product_price' => $prodData['price'],
                    'product_file_path' => 'file-manager/products/laptop.jpg',
                    'post_id' => $postId,
                    'product_views' => rand(25, 450),
                    'product_fake_views' => rand(10, 60),
                    'product_status_views' => 1,
                ]);

                // 3. Link to Category
                $categoryIdsToLink = [$category->category_id];
                if ($parentCategory && !in_array($parentCategory->category_id, $categoryIdsToLink)) {
                    $categoryIdsToLink[] = $parentCategory->category_id;
                }

                foreach ($categoryIdsToLink as $catId) {
                    DB::table('products_categories')->insertOrIgnore([
                        'product_id' => $productId,
                        'category_id' => $catId,
                    ]);
                }

                // 4. Create ProductImage
                ProductImage::create([
                    'product_image_id' => Str::uuid()->toString(),
                    'product_image_path' => $imagePath,
                    'product_image_alt' => $productName,
                    'product_id' => $productId,
                ]);

                $insertedCount++;
            }
        }

        $this->command->info("ProductSeeder completed. Seeded {$insertedCount} products across all categories.");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả post_id từ bảng posts
        $postIds = DB::table('posts')->where('post_type', 1)->pluck('post_id'); // Giả sử post_type = 1 là sản phẩm

        // Tạo 10 sản phẩm mẫu
        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'product_id' => Str::uuid(),
                'product_name' => 'Product ' . $i,
                'product_price' => rand(100, 1000),
                'product_file_path' => '/path/to/product/file-' . $i, // Thay thế bằng đường dẫn thực tế
                'post_id' => $postIds->random(), // Chọn ngẫu nhiên một post_id
                'product_views' => rand(0, 100),
                'product_fake_views' => rand(0, 50),
                'product_status_views' => rand(0, 1),
                'coupon_id' => null, // Nếu cần, bạn có thể thêm coupon_id ở đây
            ]);
        }
    }
}

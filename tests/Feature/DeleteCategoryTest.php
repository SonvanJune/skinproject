<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class DeleteCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_existing_category()
    {
        // Tạo một category mẫu
        $category = Category::create([
            'category_id' => Str::uuid()->toString(),
            'category_name' => 'New Category',
            'category_slug' => 'new-category',
            'category_image_path' => 'path/to/image',
            'category_image_alt' => 'Image alt text',
            'category_status' => 1,
            'parent_id' => null,
            'category_type' => 1,
            'category_topbar_index' => 1,
            'category_home_index' => 2,
            'category_release' => '2024-08-01',
        ]);

        // Gửi yêu cầu DELETE tới API
        $response = $this->deleteJson('/api/categories/' . $category->category_id);

        // Kiểm tra mã trạng thái trả về là 200
        $response->assertStatus(200);

        // Kiểm tra thông báo thành công
        $response->assertJson([
            'success' => true
        ]);

        // Kiểm tra category đã bị xóa khỏi database
        $this->assertDatabaseMissing('categories', [
            'category_id' => $category->category_id,
        ]);
    }

    // public function test_delete_non_existing_category()
    // {
    //     // Gửi yêu cầu DELETE tới một category không tồn tại
    //     $response = $this->deleteJson('/api/categories/999');

    //     // Kiểm tra mã trạng thái trả về là 404
    //     $response->assertStatus(404);

    //     // Kiểm tra thông báo lỗi
    //     $response->assertJson([
    //         'message' => 'Category not found',
    //     ]);
    // }
}

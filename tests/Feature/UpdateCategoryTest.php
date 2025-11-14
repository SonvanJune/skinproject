<?php
namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Str;


class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_a_category()
    {
        // Tạo danh mục ban đầu
        $category = Category::create([
            'category_id' => Str::uuid()->toString(),
            'category_name' => 'Old Name',
            'category_slug' => 'old-slug',
            'category_image_path' => 'https://via.placeholder.com/640x480.png/0022bb?text=molestiae',
            'category_image_alt' => 'At sunt accusamus veniam consequatur qui sunt.',
            'category_status' => 0,
            'parent_id' => null,
            'category_type' => 1,
            'category_topbar_index' => null,
            'category_home_index' => null,
            'category_release' => '2010-07-05',
        ]);

        // Dữ liệu cập nhật
        $updatedData = [
            'category_name' => 'Updated Name',
            'category_slug' => 'updated-slug',
            'category_image_path' => 'https://via.placeholder.com/640x480.png/0055aa?text=updated',
            'category_image_alt' => 'Updated alt text',
            'category_status' => 1,
            'parent_id' => null,
            'category_type' => 2,
            'category_topbar_index' => 1,
            'category_home_index' => 1,
            'category_release' => '2024-01-01',
        ];

        // Thực hiện yêu cầu PUT để cập nhật danh mục
        $response = $this->putJson("/api/categories/{$category->category_id}", $updatedData);

        // Kiểm tra mã trạng thái phản hồi
        $response->assertStatus(200);

        // Kiểm tra dữ liệu trong cơ sở dữ liệu
        $this->assertDatabaseHas('categories', $updatedData);
    }
}

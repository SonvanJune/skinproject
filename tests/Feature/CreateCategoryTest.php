<?php

namespace Tests\Feature;

use App\DTOs\CreateCategoryDTO;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testFromModelWithNoParent()
    {
        // Tạo một category mẫu
        $category = Category::factory()->create([
            'category_name' => 'Test Category',
            'category_slug' => 'test-category',
            'category_image_path' => 'path/to/image.jpg',
            'category_image_alt' => 'Test Image',
            'category_status' => true,
            'category_type' => 1,
            'parent_id' => null,
            'category_topbar_index' => 1,
            'category_home_index' => 2,
            'category_release' => now(),
        ]);

        // Chuyển đổi category thành DTO
        $dto = CreateCategoryDTO::fromModel($category);

        // Kiểm tra các giá trị
        $this->assertEquals('Test Category', $dto->name);
        $this->assertEquals('test-category', $dto->slug);
        $this->assertEquals('path/to/image.jpg', $dto->image_path);
        $this->assertEquals('Test Image', $dto->image_alt);
        $this->assertTrue($dto->status);
        $this->assertEquals(0, $dto->level);
        $this->assertNull($dto->parent);
        $this->assertEquals('normal', $dto->type);
        $this->assertEquals(1, $dto->topbar_index);
        $this->assertEquals(2, $dto->home_index);
        $this->assertEquals($category->category_release, $dto->release);
    }

    public function testFromModelWithParent()
    {
        // Tạo category cha
        $parentCategory = Category::factory()->create([
            'category_name' => 'Parent Category',
            'category_level' => 0,
        ]);

        // Tạo category con
        $childCategory = Category::factory()->create([
            'category_name' => 'Child Category',
            'parent_id' => $parentCategory->category_id,
        ]);

        // Chuyển đổi category con thành DTO
        $dto = CreateCategoryDTO::fromModel($childCategory);

        // Kiểm tra các giá trị
        $this->assertEquals('Child Category', $dto->name);
        $this->assertEquals(1, $dto->level);
        $this->assertInstanceOf(CreateCategoryDTO::class, $dto->parent);
        $this->assertEquals('Parent Category', $dto->parent->name);
    }
}



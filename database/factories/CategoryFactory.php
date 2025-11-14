<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'category_id' => $this->faker->uuid,
            'category_name' => $this->faker->word,
            'category_slug' => $this->faker->slug,
            'category_image_path' => $this->faker->imageUrl(),
            'category_image_alt' => $this->faker->sentence,
            'category_status' => $this->faker->numberBetween(0, 1),
            'parent_id' => $this->faker->optional()->uuid,
            'category_type' => $this->faker->numberBetween(1, 10),
            'category_topbar_index' => $this->faker->optional()->numberBetween(1, 100),
            'category_home_index' => $this->faker->optional()->numberBetween(1, 100),
            'category_release' => $this->faker->optional()->date(),
        ];
    }
}

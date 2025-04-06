<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CategoryItem;

class CategoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "item_id" => $this->faker->numberBetween(1,10),
            "category_id" => $this->faker->numberBetween(1,14)
        ];
    }
}

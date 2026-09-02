<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'condition_id' => Condition::inRandomOrder()->first()->id,
            'name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(100, 50000),
            'image' => 'items/watch.jpg',
            'status' => 'selling',
        ];
    }
}

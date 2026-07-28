<?php

namespace Database\Factories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Condition::class;

    public function definition()
    {
        return [
            'name' => '良好',
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Log::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'source' => $this->faker->word(),
            'source_id' => $this->faker->randomNumber(),
            'message' => $this->faker->sentence()
        ];
    }
}

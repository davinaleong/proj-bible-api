<?php

namespace Database\Factories;

use App\Models\Copyright;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CopyrightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Copyright::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'name' => $this->faker->name,
            'text' => $this->faker->sentence,
            'created_by' => $user,
            'updated_by' => $user,
        ];
    }
}

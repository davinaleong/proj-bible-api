<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VerseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Verse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'chapter_id' => Chapter::factory()->create(),
            'number' => '01',
            'passage' => $this->faker->sentence,
            'created_by' => $user,
            'updated_by' => $user
        ];
    }
}

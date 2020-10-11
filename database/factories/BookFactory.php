<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'translation_id' => Translation::factory()->create(),
            'name' => $this->faker->name,
            'abbr' => $this->faker->word,
            'number' => 1,
            'chapter_limit' => 1,
            'created_by' => $user,
            'updated_by' => $user
        ];
    }
}

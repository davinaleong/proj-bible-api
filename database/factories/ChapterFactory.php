<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'book_id' => Book::factory()->create(),
            'number' => 1,
            'verse_limit' => 1,
            'created_by' => $user,
            'updated_by' => $user
        ];
    }
}

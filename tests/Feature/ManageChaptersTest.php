<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageChaptersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $this->get(route('chapters.showChapter', ['chapter' => 1]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->get(route('chapters.create', ['translation' => $translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('chapters.store', ['translation' => $translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }
}

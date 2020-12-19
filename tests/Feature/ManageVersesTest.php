<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageVersesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_endpoints()
    {
        $verse = Verse::factory()->create();

        $this->get(route('verses.showVerse', ['verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('verses.create', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('verses.store', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('verses.show', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('verses.edit', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->patch(route('verses.update', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('verses.destroy', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();
        $verse = Verse::factory()->create();

        $this->actingAs($user)
            ->get(route('verses.create', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('verses.show', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('verses.edit', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertOk();
    }

    /** @test */
    public function user_accessing_showverse_results_in_redirect()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $verse = Verse::factory()->create();

        $this->actingAs($user)
            ->get(route('verses.showVerse', ['verse' => $verse]))
            ->assertRedirect(route('verses.show',
                ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]));
    }

    public function user_can_create_a_verse()
    {
        $user = User::factory()->create();
        $verse = Verse::factory()->make();
        $verse_id = 1;

        $this->actingAs($user)
            ->post(route('verses.create', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter
            ]), [
                'number' => $verse->number,
                'passage' => $verse->passage
            ])
            ->assertSessionHas('message', 'Verse created.');

        $this->assertDatabaseHas(Table::$TABLE_VERSES, [
            'chapter_id' => $verse->chapter_id,
            'number' => $verse->number,
            'passage' => $verse->passage,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $translation = $verse->chapter->book->translation;
        $book = $verse->chapter->book;
        $chapter = $verse->chapter;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_VERSES,
            'source_id' => $verse_id,
            'message' => "$user->name created verse $verse->number for $chapter->number, $book->name, $translation->abbr."
        ]);
    }
}

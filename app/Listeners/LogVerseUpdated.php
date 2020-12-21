<?php

namespace App\Listeners;

use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVerseUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if(auth()->user()) {
            $name = auth()->user()->name;
            $verse = $event->verse;
            $chapter = $verse->chapter;
            $book_name = $verse->chapter->getBookName();
            $abbr = $verse->chapter->book->getTranslationAbbr();
            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Table::$TABLE_VERSES,
                'source_id' => $verse->id,
                'message' => "$name updated verse $verse->number for $chapter->number, $book_name, $abbr."
            ]);
        }
    }
}

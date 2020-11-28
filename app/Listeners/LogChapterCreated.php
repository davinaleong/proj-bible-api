<?php

namespace App\Listeners;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogChapterCreated
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
            $chapter = $event->chapter;
            $book_name = $event->chapter->getBookName();
            $abbr = $event->chapter->book->getTranslationAbbr();
            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Log::$TABLE_CHAPTERS,
                'source_id' => $chapter->id,
                'message' => "$name created chapter $chapter->number for $book_name, $abbr."
            ]);
        }
    }
}

<?php

namespace App\Listeners;

use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogChapterDeleted
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
                'source' => Table::$TABLE_CHAPTERS,
                'source_id' => $chapter->id,
                'message' => "$name deleted chapter $chapter->number for $book_name, $abbr."
            ]);
        }
    }
}

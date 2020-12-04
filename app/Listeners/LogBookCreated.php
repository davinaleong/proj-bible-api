<?php

namespace App\Listeners;

use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogBookCreated
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
            $book = $event->book;
            $abbr = $event->book->getTranslationAbbr();
            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Table::$TABLE_BOOKS,
                'source_id' => $book->id,
                'message' => "$name created book $book->name for $abbr."
            ]);
        }
    }
}

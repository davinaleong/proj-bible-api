<?php

namespace App\Listeners;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTranslationDeleted
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
            $translation_name = $event->translation->name;

            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Log::$TABLE_TRANSLATIONS,
                'source_id' => $event->translation->id,
                'message' => "$name deleted translation $translation_name. All translation's books, chapters & verses also deleted."
            ]);
        }
    }
}

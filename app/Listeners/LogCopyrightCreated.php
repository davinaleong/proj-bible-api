<?php

namespace App\Listeners;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCopyrightCreated
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
            $copyright_name = $event->copyright->name;

            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Log::$TABLE_COPYRIGHTS,
                'source_id' => $event->copyright->id,
                'message' => "$name created copyright $copyright_name."
            ]);
        }
    }
}

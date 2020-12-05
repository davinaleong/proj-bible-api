<?php

namespace App\Listeners;

use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCopyrightUpdated
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
                'source' => Table::$TABLE_COPYRIGHTS,
                'source_id' => $event->copyright->id,
                'message' => "$name updated copyright $copyright_name."
            ]);
        }
    }
}

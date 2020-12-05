<?php

namespace App\Listeners;

use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserPasswordUpdated
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
        $name = auth()->user()->name;

        Log::create([
            'user_id' => auth()->user()->id,
            'source' => Table::$TABLE_USERS,
            'source_id' => $event->user->id,
            'message' => "$name changed his/her password."
        ]);
    }
}

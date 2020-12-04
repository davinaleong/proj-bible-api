<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use App\Models\Log;
use App\Models\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserUpdated
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
    public function handle(UserUpdated $event)
    {
        if (auth()->user()) {
            $name = auth()->user()->name;

            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Table::$TABLE_USERS,
                'source_id' => $event->user->id,
                'message' => "$name updated his/her profile."
            ]);
        }
    }
}

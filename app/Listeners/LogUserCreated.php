<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserCreated
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
    public function handle(UserCreated $event)
    {
        if(auth()->user()) {
            $name = auth()->user()->name;
            Log::create([
                'user_id' => auth()->user()->id,
                'source' => Log::$TABLE_USERS,
                'source_id' => $event->user->id,
                'message' => "Profile of $name created."
            ]);
        }
    }
}

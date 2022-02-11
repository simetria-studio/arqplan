<?php

namespace App\Listeners\Audit;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Audit;

class LogSuccessfulLogout
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
        Audit::create([
            'user_id' => $event->user->id, 
            'type' => 'LOGOUT',
            'description' =>'O usuário  '. $event->user->name .' saiu do sistema!'
        ]);
    }
}

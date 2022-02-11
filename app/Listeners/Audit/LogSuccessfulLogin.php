<?php

namespace App\Listeners\Audit;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Audit;

class LogSuccessfulLogin
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
            'type' => 'LOGIN',
            'description' =>'O usuário '. $event->user->name .' entrou no sistema!'
        ]);
    }
}

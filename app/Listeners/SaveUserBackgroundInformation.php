<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserBackgroundInformation
{

    public $users;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserServices $user)
    {
        $this->users = $user;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserSaved  $event
     * @return void
     */
    public function handle(UserSaved $event)
    {
        app('log')->info($event->user);
        $this->users->details($event->user);
    }
}

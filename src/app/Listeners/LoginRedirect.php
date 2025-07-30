<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redirect;

class LoginRedirect
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
     * @param  Authenticated  $event
     * @return mixed
     */
    public function handle(Authenticated $event)
    {
        $user = $event->user;
        if ($user && $user->profile_key === 1) {
            return Redirect('/mypage/profile');
        }

        return Redirect('/');

    }
}

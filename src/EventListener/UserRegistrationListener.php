<?php

namespace App\EventListener;

use App\Event\UserRegistrationCompleted;

class UserRegistrationListener
{
    public function onRegistration(UserRegistrationCompleted $event)
    {
        dump(['inside listener', $event]);
    }
}

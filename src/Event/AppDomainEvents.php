<?php


namespace App\Event;

/**
 * Centralize all Business events thrown by the App.
 */
final class AppDomainEvents
{
    //...

    /**
     * @Event("App\Event\UserRegistrationCompleted")
     */
    public const USER_REGISTRATION_COMPLETED = 'app.user_registration_completed';

    //...
}

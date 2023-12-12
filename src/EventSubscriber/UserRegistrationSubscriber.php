<?php

namespace App\EventSubscriber;

use App\Event\AppDomainEvents;
use App\Event\UserRegistrationCompleted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegistrationSubscriber implements EventSubscriberInterface
{
    public function onAppUserRegistrationCompleted(UserRegistrationCompleted $event): void
    {
        $this->sendConfirmationEmail($event);
        $this->sendNotificationToDiscord($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppDomainEvents::USER_REGISTRATION_COMPLETED => 'onAppUserRegistrationCompleted',
        ];
    }

    private function sendConfirmationEmail(UserRegistrationCompleted $event)
    {
        $email = [
            'recipient' => $event->getUser()->getEmail(),
            'subject' => sprintf('Welcome %s on SensioTV', $event->getUser()->getFirstName()),
        ];

        dump($email);
    }

    private function sendNotificationToDiscord(UserRegistrationCompleted $event)
    {
        $discordMessage = [
            'content' => sprintf('SensioTV Team a new user %s has created an account 🥳', $event->getUser()->getEmail())
        ];

        dump($discordMessage);
    }
}

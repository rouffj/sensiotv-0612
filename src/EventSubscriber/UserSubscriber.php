<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered($event)
    {
        $this->sendConfirmationEmail($event);
    }
    
    private function sendConfirmationEmail($event)
    {
        $user = $event->getUser();
        
        $email = [
            'from' => 'team@sensiotv.io',
            'to' => $user->getEmail(),
            'subject' => sprintf('Bonjour %s, ravi de vous voir sur sensioTV', $user->getFirstName()),
        ];
        
        dump($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}

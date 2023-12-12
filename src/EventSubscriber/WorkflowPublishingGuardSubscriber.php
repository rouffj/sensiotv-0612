<?php

namespace App\EventSubscriber;

use App\Entity\Movie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class WorkflowPublishingGuardSubscriber implements EventSubscriberInterface
{
    const BLACKLIST_WORDS = [
        'red',
    ];

    public function onWorkflowMoviePublishingGuardToPublish(GuardEvent $event): void
    {
        /** @var Movie $movie */
        $movie = $event->getSubject();

        foreach (self::BLACKLIST_WORDS as $word) {
            if (str_contains(strtolower($movie->getTitle()), $word)) {
                $event->setBlocked(true, 'A word blacklisted is used on the movie');
            }
        }

        $titleLength = $event->getMetadata('title_length', $event->getTransition());
        if (strlen($movie->getTitle()) < $titleLength) {
            $explanation = $event->getMetadata('explanation', $event->getTransition());
            $event->setBlocked(true, $explanation);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.movie_publishing.guard.to_publish' => 'onWorkflowMoviePublishingGuardToPublish',
        ];
    }
}

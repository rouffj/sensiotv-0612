<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const VIEW = 'MOVIE_VIEW';

    protected function supports(string $action, mixed $entity): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($action, [self::VIEW])
            && $entity instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute(string $action, mixed $movie, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (self::VIEW === $action) {
            return $user->getBirthday() <= $movie->getReleaseDate();
        }

        return false;
    }
}

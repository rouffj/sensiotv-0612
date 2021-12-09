<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    const BLACKLIST = [
        'Sheat',
        'Hassle',
        'Fuck',
        'Sky',
    ];
    
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MOVIE_SHOW'])
            && $subject instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute(string $action, $movie, TokenInterface $token): bool
    {
        /** @var App\Entity\User */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($action) {
            case 'MOVIE_SHOW':
            foreach (self::BLACKLIST as $forbiddenWord) {
                if (false !== strpos($movie->getTitle(), $forbiddenWord)) {
                    return false;
                }
            }
                
                break;
        }

        return true;
    }
}

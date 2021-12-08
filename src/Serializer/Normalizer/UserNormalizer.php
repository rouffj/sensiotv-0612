<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $userArray = $this->normalizer->normalize($object, $format, $context);
        
        unset($userArray['password']); // ignore password
        
        // Add some HATOES Links to help clients to navigate.
        $userArray['links'] = [
            'create_link' => 'POST /users',
            'show_link' => 'GET /users/' . $userArray['id'],
        ];
        // Here: add, edit, or delete some data

        return $userArray;
    }

    public function supportsNormalization($entity, $format = null): bool
    {
        return $entity instanceof \App\Entity\User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}

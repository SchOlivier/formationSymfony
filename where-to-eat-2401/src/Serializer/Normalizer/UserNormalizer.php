<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($user, $format = null, array $context = []): array
    {
        // ->normalize can return an Object or the value returned by the CIRCULAR_REFERENCE_HANDLER. Both should be handled
        $userArray = $this->normalizer->normalize($user, $format, $context);

        if (isset($context['action']) && in_array('list', $context['action'])) {
            return [
                'firstName' => $userArray['firstName'],
                'lastName' => $userArray['lastName'],
                'email' => $userArray['email']
            ];
        }

        return $userArray;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \App\Entity\User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}

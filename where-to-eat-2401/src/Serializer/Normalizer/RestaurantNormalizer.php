<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class RestaurantNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($restaurant, $format = null, array $context = []): array
    {
        $context[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER] = function($object, $format, $context) {
            return $object->getId();
        };
        $restaurantArray = $this->normalizer->normalize($restaurant, $format, $context);

        if (isset($context['groups']) &&  in_array('restaurantList', $context['groups'])) {
            return [
                $restaurantArray['id'],
                $restaurantArray['name']
            ];
        }

        return $restaurantArray;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof \App\Entity\Restaurant;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}

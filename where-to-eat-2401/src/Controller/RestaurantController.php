<?php

namespace App\Controller;

use App\Repository\InMemoryRestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Restaurants :
 * GET /restaurants/{id}    // show
 * GET /restaurants         // listing
 * POST /restaurants        // add a new restaurant
 * PATCH /restaurants/{id}/upvote|downvote
 * 
 * Users :
 * POST /users              // add a new user
 * 
 */
class RestaurantController extends AbstractController
{
    private InMemoryRestaurantRepository $restaurantRepository;

    public function __construct(InMemoryRestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function list(): Response
    {
        $restaurants = $this->restaurantRepository->findAll();

        return $this->json($restaurants, 200, [], ['groups' => ['restaurantList']]);
        // return $this->json($restaurants, 200, [], ['groups' => []]);
    }

    /**
     * @Route("/restaurants/{id}",
     * name="restaurant",
     * requirements={"id":"\d+"},
     * methods="GET")
     */
    public function show(int $id): Response
    {
        $restaurant = $this->restaurantRepository->findOneById($id);

        // return $this->json($restaurant, 200, [], [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object, $format, $context) {
        //     return $object->getId();
        // }]);

        return $this->json($restaurant, 200, [], []);
    }

    private function getRestaurants(){
        return [
            1 => ['name' => 'restau indien', 'adress' => '...'],
            2 => ['name' => 'restau chinois', 'adress' => '...'],
            3 => ['name' => 'restau italien', 'adress' => '...'],
    ];

    }
}

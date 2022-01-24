<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\Address;

class InMemoryRestaurantRepository
{
    public function findOneById($id): ?Restaurant
    {
        $restaurants = array_filter($this->findAll(), function(Restaurant $restaurant) use ($id) {
            return $restaurant->getId() == $id;
        });

        // Return the first restaurant only
        return reset($restaurants);
    }

    public function findAll()
    {
        $r1 = new Restaurant(1);
        $r1
            ->setName('Hoki Sushi')
            ->setLikes(5)
            ->setDislikes(1);

        $r2 = new Restaurant(2);
        $r2
            ->setName('Le 5 Sens')
            ->setLikes(25)
            ->setDislikes(2);

        $r3 = new Restaurant(3);
        $r3
            ->setName('Villa Min')
            ->setLikes(12)
            ->setDislikes(3);

        $addr1 = new Address();
        $addr1
            ->setStreet('2 Place de la Renaissance')
            ->setZipCode('92270')
            ->setCity('Bois-Colombes');

        $addr2 = new Address();
        $addr2
            ->setStreet('12 Place de la Renaissance')
            ->setZipCode('92270')
            ->setCity('Bois-Colombes');

        $addr3 = new Address();
        $addr3
            ->setStreet('29 Avenue Marceau')
            ->setZipCode('92400')
            ->setCity('Courbevoie');
            
        $r1->setAddress($addr1);
        $r2->setAddress($addr2);
        $r3->setAddress($addr3);

        return [$r1, $r2, $r3];

        return [$r1, $r2, $r3];
    }
}
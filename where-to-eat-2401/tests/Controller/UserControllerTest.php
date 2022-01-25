<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');

        dump($client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $users = json_decode($client->getResponse()->getContent(), true);

        // $this->assertCount(3, $users);
        $this->assertEquals('oscharff@inpi.fr', $users[0]['email']);

        // $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testUserCreat(){
        $client = static::createClient();
        $client->request('POST', '/users', [], [], [], json_encode([
            'firstName' => 'Jean',
            'lastName' => 'Jacques',
            'email' => 'JJ@free.fr',
            'password' => 'mdp123456'
        ]));
        $this->assertResponseIsSuccessful();

        /** @var UserRepository */
        $userRepo = $client->getContainer()->get(UserRepository::class);
        $createdUser = $userRepo->findOneBy(['email' => 'JJ@free.fr']);

        $this->assertEquals('JJ@free.fr', $createdUser->getEmail());
    }
}

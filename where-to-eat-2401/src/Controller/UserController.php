<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * mettre la route dans la classe permet de préfixer toutes les actions
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="create", methods="POST", defaults={"_format":"json"})
     */
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $newUserInfo = $request->getContent();

        $user = $serializer->deserialize($newUserInfo, User::class, 'json');
        
        // @TODO Insert in database
        dump($user);
        
        return $this->json($user, 201); // Statut 201 = created
    }

    /**
     * @Route("/{id}", methods="PUT")
     * 
     * @return void
     */
    public function update($id, Request $request, SerializerInterface $serializer){
        $newUserInfo = $request->getContent();

        $user = $serializer->deserialize($newUserInfo, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $this->findUserById($id)]);

        dump($user);
        return $this->json($user, 200);
    }

    /**
     * @TODO Should be in the repository (if I had a database...)-
     */
    private function findUserById($id){
        $myUser = new User();
        switch($id){
            case 1:
                $myUser->setEmail("oscharff@inpi.fr")->setFirstName('Olivier')->setLastName('Scharff');
                break;
            default:
                $myUser->setEmail("jeanJean@inpi.fr")->setFirstName('Jean')->setLastName('Jean');
        }
        return $myUser;
    }
}

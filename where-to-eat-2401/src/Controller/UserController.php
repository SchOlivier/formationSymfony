<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * mettre la route dans la classe permet de préfixer toutes les actions
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    private $validator;
    private $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="create", methods="POST", defaults={"_format":"json"})
     */
    public function create(Request $request): Response
    {
        $newUserInfo = $request->getContent();

        $user = $this->serializer->deserialize($newUserInfo, User::class, 'json');

        if ($errors = $this->runValidation($user)){
            return $errors;
        };

        // @TODO Insert in database
        dump($user);
        

        return $this->json($user, 201); // Statut 201 = created
    }

    /**
     * @Route("/{id}", methods="PUT")
     * 
     * @return void
     */
    public function update($id, Request $request){
        $newUserInfo = $request->getContent();

        $user = $this->serializer->deserialize($newUserInfo, User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $this->findUserById($id),
        ]);
        if ($errors = $this->runValidation($user)){
            return $errors;
        };
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

    private function runValidation($user){
        $errors = $this->validator->validate($user);
        if (count($errors) > 0){
            return $this->json($errors, 422);
        }
    }
}

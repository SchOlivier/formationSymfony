<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * mettre la route dans la classe permet de préfixer toutes les actions
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    private $validator;
    private $serializer;
    private $em;
    private $userRepo;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserRepository $userRepo)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->em = $entityManager;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("", name="all", methods="GET")
     */
    public function list(): Response
    {
        $users = $this->userRepo->findAll();

        return $this->json($users, 200, [], ['action' => ['list']]);
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

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, 201); // Statut 201 = created
    }

    /**
     * @Route("/{id}", methods="PUT")
     * 
     * @return void
     */
    public function update($id, Request $request){
        $newUserInfo = $request->getContent();
        $myUser = $this->userRepo->find($id);

        $user = $this->serializer->deserialize($newUserInfo, User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $myUser,
        ]);
        if ($errors = $this->runValidation($user)){
            return $errors;
        };

        $this->em->flush();
        
        return $this->json($user, 200);
    }

    private function runValidation($user){
        $errors = $this->validator->validate($user);
        if (count($errors) > 0){
            return $this->json($errors, 422);
        }
    }
}

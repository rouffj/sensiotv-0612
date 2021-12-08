<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
1 Resource REST:
User
GET    /users (list)
POST   /users (create)
DELETE /users/:userId (delete)
PUT    /users/:userId (update)
PATCH  /users/:userId (partialUpdate)
GET    /users/:userId (read)

Sub-resource REST
GET 	 /users/:userId/comments (list)

 *
 * @Route("/api/users", name="api_user_", defaults={"_format": "json"})
 */
class UserController extends AbstractController
{
    private $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    /**
     * @Route("/{id}", name="get", methods="GET")
     */
    public function show(User $user): Response
    {
        dump($user);

        // Le groupe '*' permet d'afficher toutes les propriétés peut importe leur groupe.
        $userAsJson = $this->serializer->serialize($user, 'json');
        
        return JsonResponse::fromJsonString($userAsJson);
    }
    
    /**
     * @Route("", name="list", methods="GET")
     */
    public function list(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        
        return $this->json($users, 200, [], ['groups' => ['user_list']]);
    }
    
    /**
     * @Route("", name="create", methods="POST")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $newUserAsJson = $request->getContent();
        
        $user = $this->serializer->deserialize($newUserAsJson, User::class, 'json');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->json($user, 201);
    }
    
    /**
     * @Route("/{id}", methods="PATCH", name="update")
     */
    public function update(User $user, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $json = $request->getContent();
        $updatedUser = $this->serializer->deserialize($json, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        
        $errors = $validator->validate($updatedUser);
        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }

        $entityManager->flush();

        return $this->json($updatedUser, 200);
    }
}
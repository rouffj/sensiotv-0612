<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->add('submit', Type\SubmitType::class, [
            'label' => 'Create your SensioTV account'
        ]);
            
        $userForm->handleRequest($request);
        
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            //$user = $userForm->getData();
            
            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);
            # Insert in DB
        }

        return $this->render('user/register.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/login", name="user_signin")
     */
    public function signin(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}

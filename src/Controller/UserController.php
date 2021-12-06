<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(): Response
    {
        return $this->render('user/register.html.twig');
    }

    /**
     * @Route("/login", name="user_signin")
     */
    public function signin(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}

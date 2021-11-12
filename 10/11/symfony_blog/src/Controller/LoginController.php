<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET','POST'])]
    public function index( AuthenticationUtils $authenticationUtils ): Response
    {
        // отримуємо помилку, якщо така є
        $error = $authenticationUtils->getLastAuthenticationError();

        // отримуємо login, що ввів користувач з попереднього запиту, якщо такий є
        $login = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/index.html.twig',
            [
                'login'     => $login,
                'error'     => $error,
            ]
        );
    }
}

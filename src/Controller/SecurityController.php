<?php

namespace Webgiciel2\InitBureauSecurite\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'ibs_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('@InitBureauSecurite/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'ibs_logout')]
    public function logout(): void
    {
        // Symfony gère tout
    }
}

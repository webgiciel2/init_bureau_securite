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

    #[Route('/mot-de-passe-oublie', name: 'secur_forgot_password')]
    public function request(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if ($request->isMethod('POST')) {
            $email = trim($request->request->get('email'));

            $admin = $em->getRepository(SecurAdmin::class)
                ->findOneBy(['email' => $email]);

            if (!$admin) {
                $this->addFlash('danger', 'Aucun compte ne correspond à cet email.');
            } else {
                // Étape 1 : pas encore d’email, juste validation
                $this->addFlash(
                    'success',
                    'Si un compte existe, un email de réinitialisation sera envoyé.'
                );
            }
        }

        return $this->render('@InitBureauSecurite/security/forgot_password.html.twig');
    }

}

<?php

namespace Webgiciel2\InitBureauSecurite\Controller;

use Webgiciel2\InitBureauSecurite\Repository\SecurAdminRepository;
use Webgiciel2\InitBureauSecurite\Form\ActivationPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpFoundation\Response;

class ActivationController extends AbstractController
{
    #[Route('/activation', name: 'ibs_activation')]
    public function activate(
        Request $request,
        SecurAdminRepository $repository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $code = $request->query->get('code');

        if (!$code) {
            throw $this->createNotFoundException('Code manquant.');
        }

        $user = $repository->findOneByCode($code);

        if (!$user) {
            return $this->render(
                '@InitBureauSecurite/security/activation_invalid.html.twig',
                [],
                new Response('', Response::HTTP_BAD_REQUEST)
            );
        }

        if ($user->isActive()) {
            return $this->redirect('/login');
        }

        $form = $this->createForm(ActivationPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $passwordConfirm = $form->get('password_confirm')->getData();

            if ($data['password'] !== $passwordConfirm) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            } else {
                $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);

                $user->setPassword($hashedPassword);
                $user->setIsActive(true);
                $user->setCodeVerif(null);
                $user->setPasswordResetAt(new \DateTime());

                $em->flush();

                return $this->redirect('/login');
            }
        }

        return $this->render('@InitBureauSecurite/activation/activate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

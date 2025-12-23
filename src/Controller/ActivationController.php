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
            return $this->redirectToRoute('ibs_login');
        }

        $data = new ActivationPasswordData();

        $form = $this->createForm(ActivationPasswordType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data->getPassword());

            $user->setPassword($hashedPassword);
            $user->setIsActive(true);
            $user->setCodeVerif(null);
            $user->setPasswordResetAt(new \DateTimeImmutable());

            $em->flush();

            $this->addFlash('success', 'Votre compte a été activé avec succès !');
            return $this->redirectToRoute('ibs_login');
        }

        return $this->render('@InitBureauSecurite/activation/activate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

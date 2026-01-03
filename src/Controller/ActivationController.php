<?php

namespace Webgiciel2\InitBureauSecurite\Controller;

use Webgiciel2\InitBureauSecurite\Repository\SecurAdminRepository;
use Webgiciel2\InitBureauSecurite\Form\ActivationPasswordType;
use Webgiciel2\InitBureauSecurite\Form\Data\ActivationPasswordData;
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

        // recupère le code de l'url
        $code = $request->query->get('code');

        // si le code est manquant -> stop le script
        if (!$code) {
            throw $this->createNotFoundException('Code manquant.');
        }

        // récupère le user avec le code
        $user = $repository->findOneByCode($code);

        // si le user n'existe pas -> redirection vers page activation_invalid
        if (!$user) {
            return $this->render(
                '@InitBureauSecurite/security/activation_invalid.html.twig',
                [],
                new Response('', Response::HTTP_BAD_REQUEST)
            );
        }

        // si le user est activé -> redirection vers page ibs_login
        if ($user->isActive()) {
            return $this->redirectToRoute('ibs_login');
        }

        // création de l'objet ActivationPasswordData
        $data = new ActivationPasswordData();

        // récupère le formulaire mot de passe
        $form = $this->createForm(ActivationPasswordType::class, $data);
        $form->handleRequest($request);

        // si le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // hash le mot de passe 
            $hashedPassword = $passwordHasher->hashPassword($user, $data->getPassword());

            // rempli l'objet user
            $user->setPassword($hashedPassword);
            $user->setIsActive(true);
            $user->setCodeVerif(null);
            $user->setPasswordResetAt(new \DateTimeImmutable());

            // enregistre user dans la base de données
            $em->flush();

            // construit le message flash
            $this->addFlash('success', 'Votre compte a été activé avec succès !');

            // redirection vers page ibs_login
            return $this->redirectToRoute('ibs_login');
        }

        // affiche la page activate
        return $this->render('@InitBureauSecurite/activation/activate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

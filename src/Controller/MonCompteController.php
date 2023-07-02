<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

use App\Repository\AdresseRepository;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;


class MonCompteController extends AbstractController
{
    #[Route('/monCompte', name: 'app_monCompte')]
    public function index(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        UserAuthenticator $authenticator, 
        EntityManagerInterface $entityManager, 
        AuthenticationUtils $authenticationUtils,
        AdresseRepository $adresseRepository,
        FactureRepository $factureRepository,
        CommandeRepository $commandeRepository
        ): Response
    {
        
        return $this->render('monCompte/index.html.twig', [
            // 'adresses' => $adresseRepository->findAll(),
            // 'factures' => $factureRepository->findAll(),
            // 'commandes' => $commandeRepository->findAll(),
        ]);
        

        // inscription
        // $user = new User();
        // $form = $this->createForm(RegistrationFormType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     // encode the plain password
        //     $user->setPassword(
        //         $userPasswordHasher->hashPassword(
        //             $user,
        //             $form->get('plainPassword')->getData()
        //         )
        //     );

        //     $user->setDateIscriptionUser(new \DateTime());

        //     $entityManager->persist($user);
        //     $entityManager->flush();
        //     // do anything else you need here, like send an email

        //     return $userAuthenticator->authenticateUser(
        //         $user,
        //         $authenticator,
        //         $request
        //     );
        // }



        //connexion
        //  // get the login error if there is one
        //  $error = $authenticationUtils->getLastAuthenticationError();
        //  // last username entered by the user
        //  $lastUsername = $authenticationUtils->getLastUsername();

        
        // return $this->render('monCompte/index.html.twig', [
        //     //insription
        //     'registrationForm' => $form->createView(),
        //     //connexion
        //     'last_username' => $lastUsername, 'error' => $error,
           
        // ]);
    }




    

   

}

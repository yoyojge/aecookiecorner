<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailJetService;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, MailJetService $mailJetService, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setDateIscriptionUser(new \DateTime());

            //maj mercredi
            // on met le role par defaut a user
            $user->setRoles(['ROLE_USER']);

            //on renseigne le token
            $token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);
            $user->setTokenUser( $token);

            //on renseigne active
            $user->setStatusUser("notConfirmed");
            //maj mercredi

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            //on ajoute la gestion de la creation de compte par mail de conf + token
            $idNewUser = $user->getId ();
            $userObj = $userRepository->findBy( array('id' => $idNewUser ));
            $mailJetService->mailJetMailConfInscription($token, $userObj[0]);
            

            //ajout d'un message flash
            $this->addFlash('compteAjout', 'Bravo, votre compte a été correctement créé.<br /> Confirmez votre compte en cliquant sur le lien transmis par email.');



            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }




    //confirmation inscription
    #[Route('/confirmationInscription', name: 'app_ae_confirmationInscription', methods: ['GET', 'POST'])]
    public function confirmationInscription(Request $request, UserRepository $userRepository): Response
    {
        //recuperer le token
        $token = $request->query->get('token');
        

        //recuperer le user concerné
        $userNew = $userRepository->findOneBy( ['tokenUser' =>  $token ]); 
        

        //vider le token en Bdd et mettre active a true        
        $userNew->setTokenUser(null);
        $userNew->setStatusUser("Confirmed");
        $userRepository->save($userNew, true);
        // dd($userNew);

        //ajout d'un message flash
        $this->addFlash('compteAjout', 'Bravo, votre compte est maintenant complètement validé !');

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

    }    




}

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

class MotDePasseOublieController extends AbstractController
{
    //mot de passe oublié
    #[Route('/motdepasseoublie', name: 'app_ae_motdepasseoublie', methods: ['GET', 'POST'])]
    public function motDePasseOublie(Request $request, UserRepository $userRepository): Response
    {
        // //recuperer le token
        // $token = $request->query->get('token');        

        // //recuperer le user concerné
        // $userNew = $userRepository->findOneBy( ['tokenUser' =>  $token ]); 
        

        // //vider le token en Bdd et mettre active a true        
        // $userNew->setTokenUser(null);
        // $userNew->setStatusUser("Confirmed");
        // $userRepository->save($userNew, true);
        // dd($userNew);       

        return $this->render('motDePasseOublie/index.html.twig', [
            'controller_name' => 'ImportController',
            
        ]);

    }



    //mot de passe oublié Form email
    #[Route('/motdepasseoublieSendEmail', name: 'app_ae_motdepasseoublieSendEmail', methods: ['GET', 'POST'])]
    public function motDePasseOublieSendEmail(Request $request, UserRepository $userRepository, MailJetService $mailJetService, EntityManagerInterface $entityManager): Response
    {
       
        $criteria =["email"=> $_POST['emailMDPoublie']];
        $userMDPoublie = $userRepository->findBy($criteria);
        // dd($userMDPoublie);

        $token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);
        $userMDPoublie[0]->setTokenUser( $token);

        $entityManager->persist($userMDPoublie[0]);
        $entityManager->flush();

        $mailJetService->mailJetMailMdpOublie($token, $userMDPoublie[0]);            

        //ajout d'un message flash
        $this->addFlash('mdpOublie', 'La procédure pour le changement de mot de passe vous a été envoyée par email.');

        return $this->render('motDePasseOublie/index.html.twig', [
            
            
        ]);

    }



    //mot de passe oublié Form mdp
    #[Route('/motdepasseoublieSetMDP', name: 'app_ae_motdepasseoublieSetMDP', methods: ['GET', 'POST'])]
    public function motDePasseOublieSetMDP(Request $request, UserRepository $userRepository, MailJetService $mailJetService): Response
    {
       
        // //recuperer le token
        $token = $request->query->get('token');        

        // //recuperer le user concerné
        $userNew = $userRepository->findOneBy( ['tokenUser' =>  $token ]); 
        
        if(empty($token)){
            //ajout d'un message flash
            $this->addFlash('mdpOublie', "Nous n'avons pas trouvé votre adresse mail");
            return $this->redirectToRoute('app_ae_motdepasseoublie', [], Response::HTTP_SEE_OTHER);
        }
        else{
           
            return $this->render('motDePasseOublie/setMDP.html.twig', [
                'controller_name' => 'ImportController',
                'token' =>  $token,
                
            ]);
        }
    }



    //mot de passe changement
    #[Route('/motdepasseoublieChangeMDP', name: 'app_ae_motdepasseoublieChangeMDP', methods: ['GET', 'POST'])]
    public function motDePasseOublieChangeMDP(Request $request, UserRepository $userRepository, MailJetService $mailJetService, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
       
        //    dd($_POST['MDPnew']);

        $user = $userRepository->findOneBy( ['tokenUser' =>  $_POST['token'] ]); 

        $hashPsw = $userPasswordHasher->hashPassword($user, $_POST['MDPnew'] );

        $user->setPassword( $hashPsw );

        // dd( $hashPsw );

        $entityManager->persist($user);
        $entityManager->flush();
        
        $this->addFlash('mdpOublie', 'Votre mot de passe a été modifié.'); 
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        
    }




}

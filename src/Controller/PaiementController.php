<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Article;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\CommandeArticle;
use App\Service\MailJetService;
use App\Repository\AdresseRepository;
use App\Repository\ArticleRepository;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/paiment')]
class PaiementController extends AbstractController
{
    
    
    
    #[Route('/', name: 'app_paiement_index', methods: ['GET', 'POST'])]
    public function index(SessionInterface $sessionPanier, AdresseRepository $addRepo, ArticleRepository $artRepo): Response
    {
        // dd($_POST);
        $totalPanier = $_POST["totalPanier"];
        
        $panier  = $sessionPanier->get("panier", []);

        //recuperer l'id adresse de livraison
        $idAdresseLivraison = $_POST["adresseLivraisonCHBX"];
        //recuperer l'instance de l'objet adresse de livraison
        $adresseLivraison = $addRepo->find($idAdresseLivraison);
        //mettre en session l'instance de l'objet adresse de livraison
        $sessionPanier->set("adresseLivraison", $adresseLivraison);
        $addLivrSess =  $sessionPanier->get("adresseLivraison", []);

        //recuperer l'id adresse de facturation
        $idAdresseFacturation = $_POST["adresseFacturationCHBX"];
        //recuperer l'instance de l'objet adresse de livraison
        $adresseFacturation = $addRepo->find($idAdresseFacturation);
        //mettre en session l'instance de l'objet adresse de livraison
        $sessionPanier->set("adresseFacturation", $adresseFacturation);
        $addFactSess =  $sessionPanier->get("adresseFacturation", []);        
        
        return $this->render('paiement/index.html.twig', [
            "panierSess" => $panier,
            "addLivrSess" => $addLivrSess,
            "addFactSess" => $addFactSess,
            "totalPanier" => $totalPanier,
        ]);
    }




    #[Route('/process', name: 'app_paiement_process', methods: ['POST'])]
    public function process(Request $request, SessionInterface $sessionPanier, CommandeRepository $cmdRepo, FactureRepository $FactRepo, EntityManagerInterface $entityManager, ArticleRepository $artRepo, AdresseRepository $addRepo, CommandeArticleRepository $cmdArtRepo, MailJetService $mailJetService): Response
    {
        $order = json_decode($request->get('order'), true);

        $paypalId = $order['id'];
        $amount = $order['purchase_units'][0]['amount']['value'];
        $status = $order['status'];

        //on verifie que le total correspond bien au total de la commande
        $panier  = $sessionPanier->get("panier", []);
        $totalConf = 0;
        foreach ( $panier as $key => $value ){
            // echo "$key=$value<br />";
            $article = $artRepo->find($key);
            $articlePrix = $article->getPrixArticle();
            $totalConf += ($articlePrix * $value);
        }

        if($amount != $totalConf){
            return $this->render('paiement/probleme.html.twig', [   
            ]);
        }
        
        if ($amount == $totalConf && $status == 'COMPLETED') {

            //enregister la commande
            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setEtatCommande('COMPLETED');
            $commande->setTotalCommande($totalConf);

            foreach ( $panier as $key => $value ){
                
                $article = new Article();
                $article = $artRepo->find($key);
                
                $commandeArticle = new CommandeArticle();                
                $commandeArticle->setCommande($commande);
                $commandeArticle->setArticle($article);
                $commandeArticle->setQuantiteCommandeArticle( $value);

                $cmdArtRepo->save($commandeArticle);
                $entityManager->persist($commandeArticle);

                $commande->addCommandeArticle($commandeArticle);   

            }   
            
           
            $idAdresseFacturation = $sessionPanier->get("adresseFacturation", []);   
            $adresseFacturation = $addRepo->find($idAdresseFacturation);
            $commande->setAdresse($adresseFacturation);

            $idAdresseLivraison = $sessionPanier->get("adresseLivraison", []);   
            $adresseLivraison = $addRepo->find($idAdresseLivraison);
            $commande->setAdresseLivraison($adresseLivraison);

            $commande->setUser($this->getUser());


            //enregister la facture
            $facture = new Facture();
            $facture->setDateFacture(new \DateTime());
            $facture->setCommande($commande);
            $facture->setAdresse($adresseFacturation);
            $FactRepo->save($facture);
            $entityManager->persist($facture);


            // $commande->setFacture();
            $cmdRepo->save($commande);
            $entityManager->persist($commande);
            
            // $entityManager->persist($facture);
            $entityManager->flush();

            //vider le panier
            // $panier  = $sessionPanier->get("panier", []);
            $panier = [];
            $sessionPanier->set("panier", $panier);

            //envoi de mail automatique
            $mailJetService->mailJetConfirmCommande($this->getUser());
                       
            return new Response(true);
        }

        throw new HttpException(500, 'c\'est cassé');
    }


    
    #[Route('/probleme', name: 'app_paiement_probleme', methods: ['GET', 'POST'])]
    public function probleme(): Response
    {
        return $this->render('paiement/probleme.html.twig', [
            

        ]);

    }

    #[Route('/merci', name: 'app_paiement_merci', methods: ['GET', 'POST'])]
    public function merci(): Response
    {
        return $this->render('paiement/merci.html.twig', [
            

        ]);

    }





    
}




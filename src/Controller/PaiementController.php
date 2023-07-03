<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Article;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\AdresseRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/paiment')]
class PaiementController extends AbstractController
{
    
    
    
    #[Route('/', name: 'app_paiement_index', methods: ['GET', 'POST'])]
    public function index(SessionInterface $sessionPanier, AdresseRepository $addRepo, ArticleRepository $artRepo): Response
    {
        // dd($_POST);
        $totalPanier = $_POST["totalPanier"];
        
        $panier  = $sessionPanier->get("panier", []);

        //on verifie que le total correspond bien au total de la commande
        $totalConf =0;
        foreach ( $panier as $key => $value ){
            // echo "$key=$value<br />";
            $article = $artRepo->find($key);
            $articlePrix = $article->getPrixArticle();
            $totalConf += ($articlePrix * $value);
        }

        if($totalPanier != $totalConf){
            return $this->render('paiement/probleme.html.twig', [   
            ]);
        }




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
    public function process(Request $request): Response
    {
        $order = json_decode($request->get('order'), true);

        $paypalId = $order['id'];
        $amount = $order['purchase_units'][0]['amount']['value'];
        $status = $order['status'];

        $totalPanier = 5;
        $totalConf = 5;
        
        if ($totalPanier == $totalConf && $status == 'COMPLETED') {
                       
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
        return $this->render('paiement/probleme.html.twig', [
            

        ]);

    }





    
}




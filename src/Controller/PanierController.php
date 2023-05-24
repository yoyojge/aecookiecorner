<?php

namespace App\Controller;

use App\Entity\Article;
use APP\Service\PanierService;
use App\Repository\ArticleRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $sessionPanier, ArticleRepository $articleRepository): Response
    {
        
        $panier  = $sessionPanier->get("panier", []);
        $dataPanier = [];
        $totalPanier = 0;       

        foreach( $panier as $id=>$qtity){
            $article = $articleRepository->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite" => $qtity,
            ];
            $totalPanier +=  $article->getPrixArticle() * $qtity;
        }
        
        
        return $this->render('panier/index.html.twig', [
            
           "dataPanier" => $dataPanier, 
           "totalPanier" => $totalPanier
        ]);
    }





    #[Route('/addPanier', name: 'app_addPanier', methods: ['POST'])]
    public function addPanier( SessionInterface $sessionPanier, Request $request): Response
    {
        //on recupere la session "panier, si elle n'existe pas , on renvoie un tableau vide
        $panier  = $sessionPanier->get("panier", []);

        $id= $request->get('articleId');
        $qtity = $request->get('articleId');
        
        //TODO: fonction verification que l'article existe
        

        //fonction gestion panier
        if(empty($panier[$id])){
            $panier[$id] = $qtity;
        }
        else{
            $panier[$id] += $qtity;
        }
        
        $sessionPanier->set("panier", $panier);

        dd($sessionPanier);
        
        // return "ajout panier";
        //   $this->addFlash('success', 'Article ajouté !');         


    }


    // #[Route('/addPanier/{id}/{qtity}', name: 'app_addPanier')]
    // public function addPanier( $id, $qtity, SessionInterface $sessionPanier, Request $request): Response
    // {
    //     //on recupere la session "panier, si elle n'existe pas , on renvoie un tableau vide
    //     $panier  = $sessionPanier->get("panier", []);
        
    //     //TODO: fonction verification que l'article existe
        

    //     //fonction gestion panier
    //     if(empty($panier[$id])){
    //         $panier[$id] = $qtity;
    //     }
    //     else{
    //         $panier[$id] += $qtity;
    //     }
        
    //     $sessionPanier->set("panier", $panier);

    //     dd($sessionPanier);        


    // }

   

}

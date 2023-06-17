<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PanierService;
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
        
        if(!empty($panier)){
            foreach( $panier as $id=>$qtity){
                $article = $articleRepository->find($id);
                $dataPanier[] = [
                    "article" => $article,
                    "quantite" => $qtity,
                ];
                $totalPanier +=  $article->getPrixArticle() * $qtity;
            }
        }        
        
        return $this->render('panier/index.html.twig', [
            
           "dataPanier" => $dataPanier, 
           "totalPanier" => $totalPanier,
           "session" => $panier,
        ]);
    }




    #[Route('/modifPanier', name: 'app_modifPanier', methods: ['POST'])]
    public function modifPanier( Request $request, SessionInterface $sessionPanier): Response
    {       
         
        $data = $request->toArray();
        $id= intval($data['id']);
        $qtity= intval($data['qtity']);

        //AJAX
        //on peut voir les données recues dans l'outil de dev du navigateur
        //faire un dd(...mesVariables...);
        //ca generera une erreur 500, c'est normal
        // onglet reseau 
        // filtre fetch/XHR
        //cliquer sur l'url appelée => onglet appercu
        // dd( $data , $id, $qtity );

        $panier = $sessionPanier->get("panier", []);
        
        if(empty($panier[$id])){
            $panier[$id] = $qtity;
        }
        else{
            $panier[$id] += $qtity;
        }
        
        $sessionPanier->set("panier", $panier);

        $result = [];
        $result['message'] = "Panier mis a jour !!!";
        $result['idCookie'] = "id cookie: ".$id;
        $result['qtityCookie'] ="qtity cookie :". $qtity;

        $resultJson = json_encode($result);
       
        return new Response(
            $resultJson, 
            Response::HTTP_OK,
            
        );      


    }

   

    #[Route('/addPanier', name: 'app_addPanier', methods: ['POST'])]
    public function addPanier( Request $request, PanierService $panierService, SessionInterface $sessionPanier): Response
    {
        $id= $request->get('articleId');
        $qtity = $request->get('articleQtity'.$id);

        // $panierService->modifPanier( $sessionPanier, $id, $qtity);

               
        // dd($id, $qtity);

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

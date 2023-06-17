<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, SessionInterface $sessionPanier): Response
    {
        
        //recuperer les articles
        $articleTab = $articleRepository->findAll();

        $panier  = $sessionPanier->get("panier", []);
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articleTab,
            "session" => $panier,
        ]);
    }
}

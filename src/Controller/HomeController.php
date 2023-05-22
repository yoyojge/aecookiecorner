<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use App\Repository\ArticleRepository;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        
        //recuperer les articles
        $articleTab = $articleRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articleTab,
        ]);
    }
}

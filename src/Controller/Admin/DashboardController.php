<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commande;
use App\Entity\Categorie;
use App\Entity\Article;
use App\Entity\Visuel;
use App\Entity\Adresse;
use App\Entity\User;

use App\Controller\Admin\CommandeCrudController;
use App\Controller\Admin\CategorieCrudController;
use App\Controller\Admin\ArticleCrudController;
use App\Controller\Admin\VisuelCrudController;
use App\Controller\Admin\AdresseCrudController;
use App\Controller\Admin\UserCrudController;

// use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
// use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
// use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ArticleCrudController::class)->generateUrl();

        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Aecookiecorner');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        // yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-list', Commande::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Visuels', 'fas fa-list', Visuel::class);
        // yield MenuItem::linkToCrud('Adresses', 'fas fa-list', Adresse::class);
        
    }
}

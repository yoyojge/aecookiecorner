<?php

namespace App\Controller\Admin;

use App\Entity\Visuel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;


use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class VisuelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Visuel::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // return [
        //     IdField::new('id'),
        //     TextField::new('cheminVisuel'),
        // ];

        yield ImageField::new('cheminVisuel')
            ->onlyOnForms()
            ->setUploadDir('public/images/cookies')
            ->setBasePath('public/images/cookies')
            // ->setUploadedFileNamePattern('[year]/[month]/[day]/[slug]-[contenthash].[extension]')
            ->setUploadedFileNamePattern('[year]-[month]-[day]-[randomhash].[extension]')
        ;

        yield ImageField::new('cheminVisuel')
            ->onlyOnIndex()
            ->setBasePath($this->getParameter("app.path.article_visuels"))
        ;

        yield AssociationField::new('article');
    }
    



    





}

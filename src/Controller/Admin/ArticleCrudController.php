<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        // return [
           
        //     TextField::new('nomArticle'),
        //     NumberField::new('prixArticle'),
        //     TextEditorField::new('descriptionArticle'),
        //     AssociationField::new('categorie'),
        // ];    
        
        yield TextField::new('nomArticle');
        yield NumberField::new('prixArticle');
        yield TextEditorField::new('descriptionArticle');
        yield AssociationField::new('categorie');



    }
    
}

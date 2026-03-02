<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Category>
 */
class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
        // Dit à EasyAdmin: ce controller gere l'entité Category
    }

    
    public function configureFields(string $pageName): iterable
    {
        // return [
        //     IdField::new('id'),
        //     TextField::new('title'),
        //     TextEditorField::new('description'),
        // ];

        // ? Quels champs afficher dans les listeset formulaires d'admin ?
        yield IdField::new('id')->hideOnForm();
        // ? hideOnform() : l'ID s'affiche dans la liste des catégories, 
        // ? mais pas dans les formulaires de création/modification

        yield TextField::new('name', 'Nom de la catégorie');
        // ? le 2e argument de TextField::new() permet de personnaliser le label du champ dans les formulaires d'admin
    }
    
}

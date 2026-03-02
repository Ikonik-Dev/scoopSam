<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Product>
 */
class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // return [
        //     IdField::new('id'),
        //     TextField::new('title'),
        //     TextEditorField::new('description'),
        // ];

        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Nom du produit');
        yield TextEditorField::new('description', 'Description du produit');
        yield MoneyField::new('price', 'Prix du produit')->setCurrency('EUR');
        yield IntegerField::new('stock', 'Stock disponible');
        yield AssociationField::new('category', 'Catégorie du produit');

        // AssociationField gerre automatiqument la elation ManyToOne
        // Il affichera un menu deroulant avec toutes les categoruies disponibles dans la base de données
    }
    
}

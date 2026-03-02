<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // on injecte le service qui hash les mots de passe
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // todo: --- CREER L'ADMIN ---
        $admin = new User();
        $admin->setEmail('admin@shop.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        // todo: on hash le mot de passe avant de le stocker en bdd
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin1234'));
        $manager->persist($admin);

        // todo: --- CREER DES CATEGORIES ---
        $categorie1 = new Category();
        $categorie1->setName('Informatique');
        $manager->persist($categorie1);

        $categorie2 = new Category();
        $categorie2->setName('Mobilier');
        $manager->persist($categorie2);

        // todo: --- CREER DES PRODUITS ---
        $productsData = [
            ['name' => 'PC Portable', 'price' => '800.00', 'stock' => 10, 'category' => $categorie1],
            ['name' => 'Souris', 'price' => '20.00', 'stock' => 50, 'category' => $categorie1],
            ['name' => 'Clavier', 'price' => '50.00', 'stock' => 30, 'category' => $categorie1],
            ['name' => 'Bureau', 'price' => '200.00', 'stock' => 5, 'category' => $categorie2],
            ['name' => 'Chaise', 'price' => '100.00', 'stock' => 15, 'category' => $categorie2],
        ];
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setCategory($productData['category']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}

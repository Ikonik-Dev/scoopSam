<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    // le constructeur pour initialiser le repository avec l'entité Product
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Produit en stock, avec leur categorie chargée
     * @return Product[]
     */
    public function findAvailable(): array
    {
        // cette requete selectionne tous les produits qui ont du stock (stock > 0) et qui charge aussi leur categorie associée (join)
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->addSelect('c')
            ->where('p.stock > 0')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findAvailableByCategory(int $categoryId): array
    {
        // cette requete selectionne tous les produits qui ont du stock (stock > 0) et qui appartiennent à une categorie spécifique
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->addSelect('c')
            ->where('p.stock > 0')
            ->andWhere('c.id = :catId')
            ->setParameter('catId', $categoryId)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

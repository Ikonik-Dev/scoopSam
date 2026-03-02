<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    // Cette classe peut être utilisée pour gérer les commandes, par exemple en créant des commandes dans la base de données,
    // en associant des produits à une commande, etc. Elle peut également être utilisée pour générer des références de commande uniques.

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {}

    /**
    * On cree une nouvelle commande en base de données
    */
    public function createOrder(int $totalAmount): Order
    {
        $order = new Order();
        $order->setTotalAmount($totalAmount);

        // On sauvegarde en base de données
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

     /**
     * Trouve une commande par sa référence
     */
    public function findByReference(string $reference): ?Order
    {
        return $this->entityManager
            ->getRepository(Order::class)
            ->findOneBy(['reference' => $reference]);
    }

    /**
     * Marque une commande comme payée
     */
    public function markAsPaid(Order $order, string $stripeSessionId): void
    {
        $order->setStatus('paid');
        $order->setStripeSessionId($stripeSessionId);
        $this->entityManager->flush();
    }

    /**
     * Marque une commande comme annulée
     */
    public function markAsCancelled(Order $order): void
    {
        $order->setStatus('cancelled');
        $this->entityManager->flush();
    }
}
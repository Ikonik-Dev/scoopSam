<?php

namespace App\Service;

use App\Model\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    // la clé de session pour stocker le panier
    private const CART_KEY = 'shopping_cart';

    // le constructeur pour injecter le service de session
    public function __construct(
        // le service RequestStack permet d'accéder à la session de l'utilisateur
        private RequestStack $requestStack,
        // le repository de produits permet de récupérer les informations des produits à partir de leur id
        private ProductRepository $productRepository
    ) {}

    /**
     * Récupère tout le panier depuis la session
     * @return CartItem[] un tableau d'objets CartItem représentant les items du panier
     */

    public function getCart(): array
    {
        return $this->requestStack
            ->getSession()
            ->get(self::CART_KEY, []); // retourne un tableau vide si le panier n'existe pas encore
    }

    public function addItem(int $productId): void
    {
        $cart = $this->getCart();

        // On cherche le produit en base de donnée pour verifierqu'il existe ET a du stock
        $product = $this->productRepository->find($productId);

        // si le produit n'existe pas ou n'a pas de stock
        if (!$product || $product->getStock() <= 0) {
            return; // on ne fait rien
        }

        // on vérifie que les données nécessaires sont présentes
        if ($product->getId() === null || $product->getName() === null || $product->getPrice() === null) {
            return;
        }

        // si le produit est déjà dans le panier, on augmente la quantité
        if (isset($cart[$productId])) {
            // deja dans le panier, on augmente la quantité
            $cart[$productId]->setQuantity($cart[$productId]->getQuantity() + 1);
        } else {
            // Nouveau -> on cree une ligne de panier pour ce produit
            $cart[$productId] = new CartItem(
                $product->getId(),
                $product->getName(),
                (float) $product->getPrice()
            );
        }

        // on enregistre le panier mis à jour dans la session
        $this->save($cart);
    }

    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();

        // si le produit est dans le panier, on le supprime
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        // on enregistre le panier mis à jour dans la session
        $this->save($cart);
    }

    public function clear(): void
    {
        $this->save([]); // on enregistre un panier vide dans la session
    }

    /**
     * Total du panier en euros (ex: 9.99)
     */
    public function getTotal(): float
    {
        $cart = $this->getCart();

        // on calcule le total en additionnant les sous-totaux de chaque item du panier
        $total = 0;
        foreach ($cart as $item) {
            $total += $item->getSubtotal();
        }

        // on retourne le total en euros (en divisant par 100 pour convertir les centimes)
        return $total / 100;
    }

    /**
     * Nombre total d'articles dans le panier (ex: 3)
     */
    public function getTotalQuantity(): int
    {
        $count = 0;
        foreach ($this->getCart() as $item) {
            $count += $item->getQuantity();
        }
        return $count;
    }

    /**
     * Enregistre le panier dans la session
     * @param CartItem[] $cart
     */
    private function save(array $cart): void
    {
        $this->requestStack
            ->getSession()
            ->set(self::CART_KEY, $cart);
    }
}

<?php

namespace App\Model;

class CartItem
{
    // les propriétés d'un item de panier
    private int $productId;
    private string $productName;
    private float $productPrice; // en euros (ex: 9.99)
    private int $quantity;

    // le constructeur pour initialiser les propriétés d'un item de panier
    public function __construct(int $productId, string $productName, float $productPrice)
    {
        $this->productId    = $productId;
        $this->productName  = $productName;
        $this->productPrice = $productPrice;
        $this->quantity     = 1;
    }

    // les getters et setters pour accéder et modifier les propriétés d'un item de panier
    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    // une méthode pour calculer le sous-total d'un item de panier (prix unitaire * quantité)
    public function getSubtotal(): int
    {
        return ((int) round($this->productPrice * 100)) * $this->quantity;
    }

    // une méthode pour formater le sous-total d'un item de panier en euros (ex: "9,99 €")
    public function getFormattedSubtotal(): string
    {
        return number_format($this->getSubtotal() / 100, 2, ',', ' ') . ' €';
    }
}
<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeService
{
    public function __construct(private string $stripeSecretKey)
    {
        // La clé sera utilisée lors de la création de la session
    }

    /**
     * Crée une session de paiement Stripe pour les produits du panier.
     *
     * @param array $cartItems Liste des éléments du panier
     * @param string $orderReference Référence unique de la commande
     * @param string $successUrl URL de redirection en cas de succès
     * @param string $cancelUrl URL de redirection en cas d'annulation
     *
     * @throws \InvalidArgumentException Si le panier est vide
     */
    public function createCheckoutSession(
        array $cartItems,
        string $orderReference,
        string $successUrl,
        string $cancelUrl
    ): StripeSession {
        if (empty($cartItems)) {
            throw new \InvalidArgumentException('Le panier est vide, impossible de créer une session de paiement.');
        }

        // On configure la clé secrète de Stripe pour les appels à l'API
        Stripe::setApiKey($this->stripeSecretKey);

        // On prépare les éléments du panier pour la session de paiement
        $linesItems = [];

        // On parcourt les éléments du panier pour les formater selon les exigences de Stripe
        foreach ($cartItems as $item) {
            $linesItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getProduct()->getPrice() * 100, // Vérifiez que getPrice() retourne bien des euros (float/int), pas des centimes
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }
        
        // On crée la session de paiement avec les éléments du panier et les URLs de redirection
        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $linesItems,
            'mode' => 'payment',
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'order_reference' => $orderReference,
            ],
        ]);
    }
}

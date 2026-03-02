<?php

namespace App\Controller;


use App\Service\CartService;
use App\Service\OrderService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/order', name: 'app_order')]
final class OrderController extends AbstractController
{
    #[Route('checkout', name: 'checkout_success')]
    public function checkout(
        CartService $cartService,
        OrderService $orderService,
        StripeService $stripeService
    ): Response {
        // Récupérer les éléments du panier
        $cart = $cartService->getCart();

        // si le panier est vide, rediriger vers la page d'accueil
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide, impossible de procéder au paiement.');
            return $this->redirectToRoute('app_home');
        }

        // Calculer le montant total de la commande
        $totalAmount = $cartService->getTotal();

        // Créer une nouvelle commande en base de données
        $order = $orderService->createOrder($totalAmount);

        // on demande a stripe de prendre en charge le paiement
        $stripeSession = $stripeService->createCheckoutSession(
            cartItems: $cart,
            orderReference: $order->getReference(),
            successUrl: $this->generateUrl('app_order_success', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            cancelUrl: $this->generateUrl('app_order_cancel', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        );

        // ! /!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\/!\
// todo: On sauvegarde l'Id de session Stripe dans la commande

        // On redirige le visiteur vers la page Stripe
        return $this->redirect($stripeSession->url);
    }

    #[Route('/success/{reference}', name: 'app_order_success')]
    public function success(): Response {
        // On retrouve la commande
        // On marque comme payée
        // On vide le panier

        return $this->render('order/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/cancel/{reference}', name: 'app_order_cancel')]
    public function cancel(): Response {
        // On retrouve la commande
        // On marque comme annulée

        return $this->render('order/cancel.html.twig', [
            'order' => $order,
        ]);
    }

}


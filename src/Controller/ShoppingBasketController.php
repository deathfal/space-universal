<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingBasketController extends AbstractController
{
    #[Route('/shopping-basket', name: 'shopping_basket')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartItems = [];
        $total = 0;

        if (!empty($cart)) {
            $products = $productRepository->findBy(['id' => array_keys($cart)]);

            foreach ($products as $product) {
                $quantity = $cart[$product->getId()];
                $cartItems[] = [
                    'id'       => $product->getId(),
                    'name'     => $product->getName(),
                    'price'    => $product->getPrice(),
                    'quantity' => $quantity,
                    'image'    => $product->getImageUrl() ?: 'img/products/default_product.png',
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('shopping/shopping_basket.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $address = $user->getAddress() ?: new Address();

        return $this->render('shopping/checkout.html.twig', [
            'user' => $user,
            'address' => $address,
        ]);
    }

    #[Route('/process-checkout', name: 'process_checkout', methods: ['POST'])]
    public function processCheckout(Request $request): Response
    {
        $street = $request->request->get('street');
        $city = $request->request->get('city');
        $postalCode = $request->request->get('postal_code');
        $planet = $request->request->get('planet');
        $galaxy = $request->request->get('galaxy');
        $country = $request->request->get('country');
        $cardNumber = $request->request->get('card_number');
        $expiryDate = $request->request->get('expiry_date');
        $cvv = $request->request->get('cvv');

        return $this->redirectToRoute('shopping_basket');
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingBasketController extends AbstractController
{
    #[Route('/shopping-basket', name: 'shopping_basket')]
    public function index(): Response
    {
        
        return $this->render('shopping/shopping_basket.html.twig');
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(int $id): Response
    {

        return $this->redirectToRoute('shopping_basket');
    }
}


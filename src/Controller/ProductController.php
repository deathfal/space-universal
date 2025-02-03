<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {

        $products = [
            ['id' => 1, 'name' => 'Produit 1', 'price' => '100 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.5],
            ['id' => 2, 'name' => 'Produit 2', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],
            ['id' => 3, 'name' => 'Produit 3', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],
            ['id' => 4, 'name' => 'Produit 1', 'price' => '100 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.5],
            ['id' => 5, 'name' => 'Produit 2', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],
            ['id' => 6, 'name' => 'Produit 3', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],
            ['id' => 7, 'name' => 'Produit 1', 'price' => '100 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.5],
            ['id' => 8, 'name' => 'Produit 2', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],
            ['id' => 9, 'name' => 'Produit 3', 'price' => '200 UEC', 'image' => 'img/AcryliPlex.jpg', 'rating' => 4.0],

        ];

        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }
}
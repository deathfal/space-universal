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
        // Exemple de données de produits
        $products = [
            ['id' => 1, 'name' => 'Produit 1', 'price' => '100 UEC', 'image' => 'path/to/image1.jpg', 'rating' => 4.5],
            ['id' => 2, 'name' => 'Produit 2', 'price' => '200 UEC', 'image' => 'path/to/image2.jpg', 'rating' => 4.0],
            // Ajoutez plus de produits ici
        ];

        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/products/{id}', name: 'product_details')]
    public function show(int $id): Response
    {
        $products = [
            1 => ['id' => 1, 'name' => 'Produit 1', 'price' => '100 UEC', 'image' => 'images/products/AcryliPlex.jpg', 'rating' => 4.5, 'description' => 'Description du produit 1'],
            2 => ['id' => 2, 'name' => 'Produit 2', 'price' => '200 UEC', 'image' => 'images/products/AcryliPlex.jpg', 'rating' => 4.0, 'description' => 'Description du produit 2'],
            3 => ['id' => 3, 'name' => 'Produit 3', 'price' => '200 UEC', 'image' => 'images/products/AcryliPlex.jpg', 'rating' => 4.0, 'description' => 'Description du produit 3'],
        ];

        if (!isset($products[$id])) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        return $this->render('products/detail.html.twig', [
            'product' => $products[$id],
        ]);
    }
}
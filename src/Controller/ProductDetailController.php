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
            1 => [
                'id' => 1,
                'name' => 'Produit 1',
                'price' => '100 UEC',
                'image' => 'img/AcryliPlex.jpg',
                'rating' => 4.5,
                'description' => 'Description du produit 1',
                'images' => [
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg'
                ],
                'features' => [
                    'Feature 1',
                    'Feature 2',
                    'Feature 3'
                ],
                'specs' => [
                    'Weight' => '1.2 kg',
                    'Dimensions' => '20 x 10 x 5 cm',
                    'Material' => 'Acrylic'
                ]
            ],
            2 => [
                'id' => 2,
                'name' => 'Produit 2',
                'price' => '200 UEC',
                'image' => 'img/AcryliPlex.jpg',
                'rating' => 4.0,
                'description' => 'Description du produit 2',
                'images' => [
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg'
                ],
                'features' => [
                    'Feature 1',
                    'Feature 2',
                    'Feature 3'
                ],
                'specs' => [
                    'Weight' => '1.5 kg',
                    'Dimensions' => '25 x 12 x 6 cm',
                    'Material' => 'Acrylic'
                ]
            ],
            3 => [
                'id' => 3,
                'name' => 'Produit 3',
                'price' => '200 UEC',
                'image' => 'img/AcryliPlex.jpg',
                'rating' => 4.0,
                'description' => 'Description du produit 3',
                'images' => [
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg',
                    'img/AcryliPlex.jpg'
                ],
                'features' => [
                    'Feature 1',
                    'Feature 2',
                    'Feature 3'
                ],
                'specs' => [
                    'Weight' => '1.8 kg',
                    'Dimensions' => '30 x 15 x 7 cm',
                    'Material' => 'Acrylic'
                ]
            ],
        ];

        if (!isset($products[$id])) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        return $this->render('products/detail.html.twig', [
            'product' => $products[$id],
        ]);
    }
}
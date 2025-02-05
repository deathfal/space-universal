<?php
// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\FeedbackRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
        FeedbackRepository $feedbackRepository
    ): Response {
        return $this->render('index.html.twig', [
            'products' => $productRepository->findAll(),
            'feedbacks' => $feedbackRepository->findBy([], ['createdAt' => 'DESC'], 6)
        ]);
    }
}
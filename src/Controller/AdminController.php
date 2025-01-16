<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        // In a real application, you would fetch this data from your database or services
        $data = [
            'total_revenue' => 45231.89,
            'revenue_increase' => 20.1,
            'active_users' => 2350,
            'user_increase' => 180.1,
            'products_in_stock' => 12234,
            'stock_increase' => 19,
            'active_sales' => 573,
            'sales_increase' => 201,
            'chart_labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'chart_data' => [4000, 3000, 5000, 4500, 6000, 5500],
        ];

        return $this->render('admin/dashboard.html.twig', $data);
    }

    #[Route('/admin/products', name: 'admin_products')]
    public function products(): Response
    {
        // Fetch products from database
        $products = [
            ['id' => 1, 'name' => 'Quantum Entanglement Device', 'category' => 'Gadgets', 'stock' => 50, 'price' => 1299.99],
            ['id' => 2, 'name' => 'Nebula in a Bottle', 'category' => 'Collectibles', 'stock' => 30, 'price' => 149.99],
            ['id' => 3, 'name' => 'Anti-Gravity Boots', 'category' => 'Apparel', 'stock' => 100, 'price' => 599.99],
        ];

        return $this->render('admin/products.html.twig', ['products' => $products]);
    }

    #[Route('/admin/orders', name: 'admin_orders')]
    public function orders(): Response
    {
        // Fetch orders from database
        $orders = [
            ['id' => 1, 'customer' => 'John Doe', 'date' => '2023-06-15', 'status' => 'Shipped', 'total' => 1449.98],
            ['id' => 2, 'customer' => 'Jane Smith', 'date' => '2023-06-14', 'status' => 'Processing', 'total' => 599.99],
            ['id' => 3, 'customer' => 'Bob Johnson', 'date' => '2023-06-13', 'status' => 'Delivered', 'total' => 149.99],
        ];

        return $this->render('admin/orders.html.twig', ['orders' => $orders]);
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function users(): Response
    {
        // Fetch users from database
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Customer', 'orders' => 5],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Admin', 'orders' => 0],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'Customer', 'orders' => 2],
        ];

        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    #[Route('/admin/reviews', name: 'admin_reviews')]
    public function reviews(): Response
    {
        // Fetch reviews from database
        $reviews = [
            ['id' => 1, 'product' => 'Quantum Entanglement Device', 'rating' => 5, 'comment' => 'Great product!', 'user' => 'John Doe'],
            ['id' => 2, 'product' => 'Nebula in a Bottle', 'rating' => 4, 'comment' => 'Beautiful, but fragile', 'user' => 'Jane Smith'],
            ['id' => 3, 'product' => 'Anti-Gravity Boots', 'rating' => 5, 'comment' => 'These are amazing!', 'user' => 'Bob Johnson'],
        ];

        return $this->render('admin/reviews.html.twig', ['reviews' => $reviews]);
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Feedback;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\PaymentMethod;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        // Create Admin User
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'adminpassword');
        $adminUser->setPassword($hashedPassword);
        $manager->persist($adminUser);

        // Create Users
        $users = [];
        for ($i = 1; $i <= 3; ++$i) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setUsername("user$i");
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, "password$i");
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        // Flush users first to ensure they have IDs
        $manager->flush();

        // Create Addresses
        $addresses = [];
        for ($i = 1; $i <= 3; ++$i) {
            $address = new Address();
            $address->setStreet("Street $i");
            $address->setCity("City $i");
            $address->setPostalCode("PostalCode $i");
            $address->setPlanet("Planet $i");
            $address->setGalaxy("Galaxy $i");
            $address->setCountry("Country $i");
            $address->setCreatedAt(new \DateTime());
            $manager->persist($address);
            $addresses[] = $address;
        }

        // Create Categories
        $categories = [];
        for ($i = 1; $i <= 3; ++$i) {
            $category = new Category();
            $category->setName("Category $i");
            $category->setDescription("Description $i");
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create Products
        $products = [];
        for ($i = 1; $i <= 3; ++$i) {
            $product = new Product();
            $product->setName("Product $i");
            $product->setDescription("Description $i");
            $product->setPrice(100.0 * $i);
            $product->setStock(10 * $i);
            $product->setCategory($categories[$i - 1]);
            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
            $products[] = $product;
        }

        // Create Carts
        $carts = [];
        for ($i = 1; $i <= 3; ++$i) {
            $cart = new Cart();
            $cart->setUser($users[$i - 1]);
            $manager->persist($cart);
            $carts[] = $cart;
        }

        // Create CartItems
        for ($i = 1; $i <= 3; ++$i) {
            $cartItem = new CartItem();
            $cartItem->setCart($carts[$i - 1]);
            $cartItem->setProduct($products[$i - 1]);
            $cartItem->setQuantity($i);
            $manager->persist($cartItem);
        }

        // Create Orders
        $orders = [];
        for ($i = 1; $i <= 3; ++$i) {
            $order = new Order();
            $order->setUser($users[$i - 1]);
            $order->setBillingAddress($addresses[$i - 1]);
            $order->setShippingAddress($addresses[$i - 1]);
            $order->setTotalPrice(100.0 * $i);
            $order->setStatus('Delivered');
            $order->setCreatedAt(new \DateTime());
            $manager->persist($order);
            $orders[] = $order;
        }

        // Create OrderItems
        for ($i = 1; $i <= 3; ++$i) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($orders[$i - 1]);
            $orderItem->setProduct($products[$i - 1]);
            $orderItem->setQuantity($i);
            $orderItem->setPrice(100.0 * $i);
            $manager->persist($orderItem);
        }

        // Create PaymentMethods
        for ($i = 1; $i <= 3; ++$i) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setOwner($users[$i - 1]);
            $paymentMethod->setBillingAddress($addresses[$i - 1]);
            $paymentMethod->setCardNumber("123456789012345$i");
            $paymentMethod->setExpiryDate("12/2$i");
            $paymentMethod->setCardholderName("Cardholder $i");
            $paymentMethod->setPaymentType("Type $i");
            $paymentMethod->setCreatedAt(new \DateTime());
            $manager->persist($paymentMethod);
        }

        // Create Reviews
        for ($i = 1; $i <= 3; ++$i) {
            $review = new Review();
            $review->setOwner($users[$i - 1]);
            $review->setProduct($products[$i - 1]);
            $review->setRating($i);
            $review->setComment("Comment $i");
            $review->setCreatedAt(new \DateTime());
            $manager->persist($review);
        }

        // Create feedbacks last, after all users are saved
        $feedbackComments = [
            'Really impressed with the service! The interface is intuitive and user-friendly.',
            'Great experience overall. The customer support team was very helpful.',
            'The booking process was smooth and efficient. Will definitely use again!',
            'Love how easy it is to find and book spaces. Saved me a lot of time.',
            'The space I booked exceeded my expectations. Very satisfied with the experience.',
        ];

        // Add feedbacks for admin and regular users
        $allUsers = array_merge([$adminUser], $users);
        foreach ($allUsers as $user) {
            // Create 2 feedbacks per user
            for ($i = 0; $i < 2; ++$i) {
                $feedback = new Feedback();
                $feedback->setUser($user);
                $feedback->setComment($feedbackComments[array_rand($feedbackComments)]);
                $manager->persist($feedback);
            }
        }

        $manager->flush();
    }
}

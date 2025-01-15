<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\PaymentMethod;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\Order;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create Users
        $user = new User();
        $user->setEmail('user@example.com')
            ->setPassword('password')
            ->setUsername('username')
            ->setAvatarUrl('http://example.com/avatar.png')
            ->setCreatedAt(new \DateTime())
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // Create Addresses
        $address = new Address();
        $address->setType('billing')
            ->setStreet('123 Main St')
            ->setCity('Anytown')
            ->setState('Anystate')
            ->setPostalCode('12345')
            ->setPlanet('Earth')
            ->setGalaxy('Milky Way')
            ->setCountry('USA')
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $manager->persist($address);

        // Create PaymentMethods
        $paymentMethod = new PaymentMethod();
        $paymentMethod->setUser($user)
            ->setCardNumber('4111111111111111')
            ->setExpiryDate('12/25')
            ->setCardholderName('John Doe')
            ->setPaymentType('Credit Card')
            ->setBillingAddress($address)
            ->setCreatedAt(new \DateTime());
        $manager->persist($paymentMethod);

        // Create Categories
        $category = new Category();
        $category->setName('Electronics')
            ->setDescription('Electronic gadgets and devices');
        $manager->persist($category);

        // Create Products
        $product = new Product();
        $product->setName('Smartphone')
            ->setDescription('A high-end smartphone')
            ->setPrice(699.99)
            ->setStock(50)
            ->setCategory($category)
            ->setSeller($user)
            ->setImageUrl('http://example.com/smartphone.png')
            ->setOriginPlanet('Earth')
            ->setCreatedAt(new \DateTime());
        $manager->persist($product);

        // Create Reviews
        $review = new Review();
        $review->setUser($user)
            ->setProduct($product)
            ->setRating(5)
            ->setComment('Great product!')
            ->setCreatedAt(new \DateTime());
        $manager->persist($review);

        // Create Orders
        $order = new Order();
        $order->setUser($user)
            ->setBillingAddress($address)
            ->setShippingAddress($address)
            ->setPaymentMethod($paymentMethod)
            ->setTotalPrice(699.99)
            ->setStatus('Pending')
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $manager->persist($order);

        // Create Cart
        $cart = new Cart();
        $cart->setUser($user)
            ->setCreatedAt(new \DateTime());
        $manager->persist($cart);

        // Create CartItems
        $cartItem = new CartItem();
        $cartItem->setCart($cart)
            ->setProduct($product)
            ->setQuantity(1);
        $manager->persist($cartItem);

        // Create OrderItems
        $orderItem = new OrderItem();
        $orderItem->setOrder($order)
            ->setProduct($product)
            ->setQuantity(1)
            ->setPrice(699.99);
        $manager->persist($orderItem);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            // Add dependencies if needed
        ];
    }
}
<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));

            $plainPassword = $request->request->get('plainPassword');
            $confirmPassword = $request->request->get('confirmPassword');
            if ($plainPassword) {
                if ($plainPassword === $confirmPassword) {
                    $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                } else {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');

                    return $this->redirectToRoute('app_profile_edit');
                }
            }

            $avatarFile = $request->files->get('avatar');
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    $user->setAvatarUrl('img/avatars/'.$newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'avatar.');

                    return $this->redirectToRoute('app_profile_edit');
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/address', name: 'app_profile_address')]
    public function address(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $address = new Address();
            $address->setStreet($request->request->get('street'));
            $address->setCity($request->request->get('city'));
            $address->setPostalCode($request->request->get('postalCode'));
            $address->setPlanet($request->request->get('planet'));
            $address->setGalaxy($request->request->get('galaxy'));
            $address->setCountry($request->request->get('country'));
            $address->setCreatedAt(new \DateTime());
            $address->setUser($user);

            $entityManager->persist($address);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse a été ajoutée.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/address.html.twig');
    }

    #[Route('/profile/orders', name: 'app_profile_orders')]
    public function orders(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $orders = $entityManager->getRepository(Order::class)->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('profile/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/profile/payment-methods', name: 'app_profile_payment_methods')]
    public function paymentMethods(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setCardNumber($request->request->get('cardNumber'));
            $paymentMethod->setExpiryDate($request->request->get('expiryDate'));
            $paymentMethod->setCardholderName($request->request->get('cardholderName'));
            $paymentMethod->setPaymentType($request->request->get('paymentType'));
            $paymentMethod->setOwner($user);
            $paymentMethod->setCreatedAt(new \DateTime());

            $entityManager->persist($paymentMethod);
            $entityManager->flush();
            $this->addFlash('success', 'Votre méthode de paiement a été ajoutée.');

            return $this->redirectToRoute('app_profile_payment_methods');
        }

        $paymentMethods = $entityManager->getRepository(PaymentMethod::class)->findBy(['owner' => $user]);

        return $this->render('profile/payment_methods.html.twig', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    #[Route('/profile/reviews', name: 'app_profile_reviews')]
    public function reviews(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $reviews = $entityManager->getRepository(Review::class)->findBy(['owner' => $user], ['createdAt' => 'DESC']);

        return $this->render('profile/reviews.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}

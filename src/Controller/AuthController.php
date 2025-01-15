<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


use Symfony\Component\Routing\Attribute\Route;
class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Get authentication error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Debug user state
        $user = $security->getUser();
        if ($user) {
            $this->addFlash('success', 'Logged in as: ' . $user->getUserIdentifier() . ' with roles: ' . implode(', ', $user->getRoles()));
            return $this->redirectToRoute('app_test'); // Redirect to test page
        }

        if ($error) {
            $this->addFlash('error', 'Login failed: ' . $error->getMessageKey());
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }



    #[Route(path: '/test2', name: 'app_test')]
    public function test(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return new Response('User not logged in', Response::HTTP_UNAUTHORIZED);
        }

        return new Response('Logged in as: ' . $user->getUserIdentifier() . ' with roles: ' . implode(', ', $user->getRoles()));
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/test-email', name: 'test_email')]
    public function testEmail(\Symfony\Component\Mailer\MailerInterface $mailer): Response
    {
        $email = (new \Symfony\Component\Mime\Email())
            ->from('test@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->text('This is a test email.');

        $mailer->send($email);

        return new Response('Email sent!');
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            // Debug: Log email preparation
            $this->addFlash('info', 'Preparing confirmation email for: ' . $user->getEmail());


            if (!$user->getEmail()) {
                $this->addFlash('error', 'The email address is invalid.');
                return $this->redirectToRoute('register');
            }
            // Send confirmation email
            $email = (new TemplatedEmail())
                ->from('noreply@yourdomain.com')
                ->to($user->getEmail())
                ->subject('Confirm your registration')
                ->htmlTemplate('emails/registration.html.twig')
                ->context([
                    'username' => $user->getUsername(),
                ]);

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Registration successful! Check your email for confirmation.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to send confirmation email: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_login');
        }

        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}

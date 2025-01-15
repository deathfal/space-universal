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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
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
        Security $security,
        MailerInterface $mailer
    ): Response {
        // Debug: Log the start of the registration process
        $this->addFlash('info', 'Registration process started.');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Debug: Check if the form is submitted
        if ($form->isSubmitted()) {
            $this->addFlash('info', 'Form submitted.');

            // Debug: Check if the form is valid
            if ($form->isValid()) {
                $this->addFlash('success', 'Form is valid. Processing registration.');

                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();

                // Encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setRoles(['ROLE_USER']);

                // Save the user
                try {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // Send a confirmation email
                    $email = (new TemplatedEmail())
                        ->from('noreply@yourdomain.com')
                        ->to($user->getEmail())
                        ->subject('Confirm your registration')
                        ->htmlTemplate('emails/registration.html.twig')
                        ->context([
                            'username' => $user->getUsername(),
                        ]);

                    $mailer->send($email);

                    $this->addFlash('success', 'Registration successful! A confirmation email has been sent to your inbox.');

                    return $this->redirectToRoute('login');
                } catch (\Exception $e) {
                    // Debug: Log any exception
                    $this->addFlash('error', 'An error occurred while saving the user: ' . $e->getMessage());
                }
            } else {
                // Debug: Log form validation errors
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}

<?php

namespace App\Controller\Admin\RegisterAndAuth;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Form\Fields\Administration\AdminRegistrationFields;
use App\Form\Type\Administration\AdminRegistrationType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AdminRegistrationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly RequestStack $requestStack,
        private readonly UserAuthenticator $userAuthenticator,
        private readonly UserAuthenticatorInterface $authenticator
    ) {}


    #[Route("/backstage/register", name: "admin_register")]
    public function adminRegistration(): Response
    {
        $adminRegistrationFields = new AdminRegistrationFields;
        // Create a new Admin entity
        $adminEntity = new Admin();

        $adminForm = $this->createForm(AdminRegistrationType::class, $adminRegistrationFields);
        $adminForm->handleRequest($this->requestStack->getCurrentRequest());

        // Handle the form submission
        // Check if the form is submitted and valid
        if ($adminForm->isSubmitted() && $adminForm->isValid()) {

            $adminEntity->setFirstName($adminRegistrationFields->getFirstName());
            $adminEntity->setLastName($adminRegistrationFields->getLastName());
            $adminEntity->setEmail($adminRegistrationFields->getEmail());
            $adminEntity->setPassword($this->passwordHasher->hashPassword(
                $adminEntity,
                $adminRegistrationFields->getPassword()
            ));

            $this->entityManager->persist($adminEntity);
            $this->entityManager->flush();
            // $this->addFlash('success', 'Admin registered successfully!');

            // Redirect admin after register
            try {
                $this->authenticator->authenticateUser(
                    $adminEntity,
                    $this->userAuthenticator,
                    $this->requestStack->getCurrentRequest()
                );
                return $this->redirectToRoute('admin_dashboard');
            } catch (\Exception $e) {
                // Handle authentication failure
                $this->addFlash('error', 'Authentication failed: ' . $e->getMessage());
                return $this->redirectToRoute('admin_registration');
            }
        }
        return $this->render('admin/register_and_auth/admin_registration.html.twig', [
            'adminForm' => $adminForm
        ]);
    }
}

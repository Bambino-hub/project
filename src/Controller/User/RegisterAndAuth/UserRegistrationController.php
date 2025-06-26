<?php

namespace App\Controller\User\RegisterAndAuth;

use App\Entity\User;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\User\UserRegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Fields\User\UserRegistrationField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserRegistrationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface,
        private readonly RequestStack $requestStack,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserAuthenticator $userAuthenticator,
        private readonly UserAuthenticatorInterface $authenticator
    ) {}

    #[Route("/public/user/registration", name: "user_registration")]
    public function userRegistration(): Response
    {

        $userRegistrationFields = new UserRegistrationField();
        $userEntity = new User();

        $userForm = $this->createForm(UserRegistrationType::class, $userRegistrationFields);
        $userForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $userEntity->setPseudonyme($userRegistrationFields->getPseudonyme());
            $userEntity->setEmail($userRegistrationFields->getEmail());
            $userEntity->setPassword($this->passwordHasher->hashPassword(
                $userEntity,
                $userRegistrationFields->getPassword()
            ));

            $this->entityManagerInterface->persist($userEntity);
            $this->entityManagerInterface->flush();
            //$this->addFlash('success', 'User registered successfully!');
            // Authenticate the user after registration

            try {
                $this->authenticator->authenticateUser(
                    $userEntity,
                    $this->userAuthenticator,
                    $this->requestStack->getCurrentRequest()
                );
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                // Handle authentication failure
                $this->addFlash('error', 'Authentication failed: ' . $e->getMessage());
                return $this->redirectToRoute('user_registration');
            }
        }

        return $this->render(
            'user/registerAndAuth/userRegistration.html.twig',
            [
                'userForm' => $userForm
            ]
        );
    }
}

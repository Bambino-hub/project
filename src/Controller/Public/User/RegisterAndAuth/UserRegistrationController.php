<?php

namespace App\Controller\Public\User\RegisterAndAuth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\Public\UserRegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Fields\Public\UserRegistrationField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserRegistrationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface,
        private readonly RequestStack $requestStack,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route("/public/user/registration", name: "user_registration")]
    public function userRegistration(): Response
    {

        $userRegistrationFields = new UserRegistrationField();
        $userForm = $this->createForm(UserRegistrationType::class, $userRegistrationFields);
        $userForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userEntity = new User();

            $userEntity->setPseudonyme($userRegistrationFields->getPseudonyme());
            $userEntity->setEmail($userRegistrationFields->getEmail());
            $userEntity->setPassword($this->passwordHasher->hashPassword(
                $userEntity,
                $userRegistrationFields->getPassword()
            ));
            $userEntity->setRoles(['ROLE_USER']);

            $this->entityManagerInterface->persist($userEntity);
            $this->entityManagerInterface->flush();
            $this->addFlash('success', 'User registered successfully!');
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'public/user/registerAndAuth/userRegistration.html.twig',
            [
                'userForm' => $userForm
            ]
        );
    }
}

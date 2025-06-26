<?php

namespace App\Controller\User\RegisterAndAuth;

use App\Entity\User;
use App\Form\Type\User\EmailLoginType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Fields\User\EmailLoginField;
use Exception;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailLoginController extends AbstractController
{

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer
    ) {}

    #[Route(path: '/user/email', name: 'user_email')]
    public function emailChecking()
    {
        $emailField = new EmailLoginField();

        $emailForm = $this->createForm(EmailLoginType::class, $emailField);
        $emailForm->handleRequest($this->requestStack->getCurrentRequest());

        $email = $emailField->getEmail();



        if ($emailForm->isSubmitted() && $emailForm->isValid()) {

            $currentEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            $ession = $this->requestStack->getSession();
            $ession->set('userEmail', $email);

            if ($currentEmail) {

                return $this->redirectToRoute('user_login');
            } else {

                //send email
                try {

                    $message = (new TemplatedEmail())

                        ->from('bambino@gmail.com')
                        ->to($email)
                        ->subject("verification d'email")
                        ->htmlTemplate('user/registerAndAuth/emailSend.html.twig');


                    $this->mailer->send($message);
                    $this->addFlash('success', 'Un email de confirmation vous ete  envoyer');

                    return $this->redirectToRoute('user_email');
                } catch (TransportExceptionInterface $e) {

                    $this->addFlash('email_error_sending', $e->getMessage());

                    return $this->redirectToRoute('user_email');
                }
            }
        }

        return $this->render('user/registerAndAuth/emailchecking.html.twig', [
            'emailForm' => $emailForm
        ]);
    }
}

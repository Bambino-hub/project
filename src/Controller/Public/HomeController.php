<?php

namespace App\Controller\Public;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface
    ) {}


    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render(
            'public/home/home.html.twig',
        );
    }
}

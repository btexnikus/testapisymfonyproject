<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'default_homepage')]
    #[Template('homepage.html.twig')]
    public function homepage(EntityManagerInterface $entityManager, Request $request)
    {
        return [
            
        ];
    }

}

?>
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security): Response
    {
        // Si l'utilisateur est déjà connecté et a le rôle ADMIN, rediriger vers le dashboard
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }
        
        // Sinon, rediriger vers la page de login
        return $this->redirectToRoute('admin_login');
    }
}
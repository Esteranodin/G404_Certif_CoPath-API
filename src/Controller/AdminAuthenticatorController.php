<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAuthenticatorController extends AbstractController
{
    #[Route(path: '/admin/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si user déjà connecté avec ROLE_ADMIN -> redirection dashboard
        if ($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('admin');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('adminauthenticator/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/admin/logout', name: 'admin_logout')]
    public function logout() : never
    {
        // Si on arrive ici, c'est que la route n'est pas configurée dans le firewall
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

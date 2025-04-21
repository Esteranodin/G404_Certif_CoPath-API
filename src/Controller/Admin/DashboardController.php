<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\ImgScenario;
use App\Entity\Music;
use App\Entity\Scenario;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return UserMenu::new()
            ->displayUserName(true)
            ->setName($user->getUserIdentifier());
    }

    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //

        $userCount = $this->entityManager->getRepository(User::class)->count([]);
        $campaignCount = $this->entityManager->getRepository(Campaign::class)->count([]);
        $scenarioCount = $this->entityManager->getRepository(Scenario::class)->count([]);

        $recentUsers = $this->entityManager->getRepository(User::class)
            ->findBy([], ['id' => 'DESC'], 5);
        $recentCampaigns = $this->entityManager->getRepository(Campaign::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);
        $recentScenarios = $this->entityManager->getRepository(Scenario::class)
            ->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userCount,
            'campaignCount' => $campaignCount,
            'scenarioCount' => $scenarioCount,
            'recentUsers' => $recentUsers,
            'recentCampaigns' => $recentCampaigns,
            'recentScenarios' => $recentScenarios,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CoPath')
            ->setFaviconPath('favicon.png')
            ->setDefaultColorScheme('dark')
            ->setLocales(['fr'])
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-desktop');
        // mettre à jour la route pour le retour au site
        yield MenuItem::linktoRoute('Retourner au site CoPath', 'fas fa-home', 'app_home');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');

        yield MenuItem::section('--- Utilisateurs ---', 'fa fa-users');
        yield MenuItem::linkToCrud('Liste des utilisateurs', 'fa-solid fa-ghost', User::class);

        yield MenuItem::section('--- Scénarios ---', 'fa-solid fa-wand-magic-sparkles');
        yield MenuItem::linkToCrud('Scénarios', 'fa-solid fa-pencil', Scenario::class);
        yield MenuItem::linkToCrud('Images', 'fa-solid fa-image', ImgScenario::class);
        yield MenuItem::linkToCrud('Musiques', 'fa-solid fa-headphones', Music::class);

        yield MenuItem::section('--- Campagnes ---', 'fa-solid fa-book-open');
        yield MenuItem::linkToCrud('Campagnes', 'fa-solid fa-shield', Campaign::class);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\ImgScenario;
use App\Entity\Music;
use App\Entity\Scenario;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
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
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CoPath')
            ->setFaviconPath('img/logo.png');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-desktop');

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);

        yield MenuItem::section('Scenarios');
        yield MenuItem::linkToCrud('Scenarios', 'fas fa-list', Scenario::class);
        yield MenuItem::linkToCrud('Pictures', 'fas fa-list', ImgScenario::class);
        yield MenuItem::linkToCrud('Music', 'fas fa-list', Music::class);
        
        yield MenuItem::section('Campaigns');
        yield MenuItem::linkToCrud('Campaigns', 'fas fa-list', Campaign::class);
    }
}

<?php
namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\ {
    Config\Dashboard,
    Config\MenuItem,
    Controller\AbstractDashboardController, 
    Router\CrudUrlGenerator,
};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {


        // if (!$this->getUser())
            // return $this->redirect($routeBuilder->setController(OneOfYourCrudController::class)->generateUrl());
        // redirect to some CRUD controller
        // $routeBuilder = $this->get(CrudUrlGenerator::class)->build();


        // you can also redirect to different pages depending on the current user
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // return parent::index();
        // return $this->render('@EasyAdmin/page/content.html.twig', ['content' => 'okdsaokdsaok']);
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cinema Tickets Reservation Panel');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Website', 'fa fa-globe', '/');
        
        yield MenuItem::section('Tickets');
        yield MenuItem::linkToCrud('Tickets', 'fa fa-ticket-alt', TicketCrudController::getEntityFqcn());

        yield MenuItem::section('Screenings');
        yield MenuItem::linkToCrud('Screenings', 'fa fa-clipboard', ScreeningCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Showings', 'fa fa-desktop', ShowingCrudController::getEntityFqcn());

        yield MenuItem::section('Movies');
        yield MenuItem::linkToCrud('Movies', 'fa fa-film', MovieCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Categories', 'fa fa-tag', MovieCategoryCrudController::getEntityFqcn());

        yield MenuItem::section('Cinemas');
        yield MenuItem::linkToCrud('Cinemas', 'fa fa-warehouse', CinemaCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Rooms', 'fa fa-door-closed', RoomCrudController::getEntityFqcn());

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', UserCrudController::getEntityFqcn());
    }
}

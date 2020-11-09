<?php

namespace App\Controller\Admin;

use App\Entity\Seat;
use App\Entity\Ticket;
use App\Repository\SeatRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\OptionsResolver\Options;

class TicketCrudController extends AbstractCrudController
{
    private AdminContextProvider $adminContext;

    public function __construct(FilterFactory $filterFactory, AdminContextProvider $adminContext)
    {
        $this->adminContext = $adminContext;
    }

    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield AssociationField::new('showing');
        yield DateTimeField::new('date')
            ->setFormat('d.M.y')
        ;
        yield AssociationField::new('seat')
            ->autocomplete()
            ->setFormTypeOption('allow_extra_fields', true)
            ->addJsFiles('bundles/easyadmin/js/ticket.crud.js')
        ;
        yield TextField::new('name');
        yield TextField::new('surname');
    }

}

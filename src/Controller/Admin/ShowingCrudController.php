<?php

namespace App\Controller\Admin;

use App\Entity\Showing;
use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class ShowingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Showing::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('screening')
            ->autocomplete()
        ;
        // display only room assigned to screening
        yield AssociationField::new('room')
            ->autocomplete()
            ->addJsFiles('bundles/easyadmin/js/showing.crud.js')
        ;
        yield TimeField::new('timeStart')
            ->formatValue(fn($value, Showing $entity) => $entity->getTimeStart()->format('H:i'));
        yield TimeField::new('timeEnd')
            ->formatValue(fn($value, Showing $entity) => $entity->getTimeEnd()->format('H:i'));
        yield MoneyField::new('price')->setCurrency('EUR');
        yield AssociationField::new('tickets')
            ->autocomplete()
        ;
    }

}

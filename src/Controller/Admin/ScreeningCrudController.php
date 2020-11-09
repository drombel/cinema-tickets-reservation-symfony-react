<?php

namespace App\Controller\Admin;

use App\Entity\Screening;
use App\Entity\Showing;
use App\Form\MovieScreenImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ScreeningCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Screening::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('cinema');
        yield AssociationField::new('movie');
        yield DateField::new('dateStart')
            ->setFormat('d.M.y')
        ;
        yield DateField::new('dateEnd')
            ->setFormat('d.M.y')
            ->addJsFiles('bundles/easyadmin/js/screening.dateEnd.js')
        ;
        yield AssociationField::new('showings')
            ->autocomplete()
        ;
    }
}

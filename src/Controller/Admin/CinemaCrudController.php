<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Entity\Cinema;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CinemaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cinema::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('title');
        if (Crud::PAGE_NEW !== $pageName)
            yield TextField::new('slug');
        yield TextEditorField::new('description');
        yield ImageField::new('image')
            ->setBasePath('/images/cinemas')
            ->hideOnForm();
        yield ImageField::new('imageFile')
            ->setFormType(VichImageType::class)
            ->setFormTypeOption('delete_label', 'Remove image?')
            ->onlyOnForms();
        yield AssociationField::new('rooms')
            ->autocomplete();
        yield TextField::new('address');
        yield TextField::new('lat');
        yield TextField::new('long');
        yield AssociationField::new('screenings')
            ->autocomplete();

        yield DateTimeField::new('updatedAt')
            ->formatValue(fn(string $value, $entity) => $entity->getUpdatedAt()->format('H:i:s d.m.Y'))
            ->onlyOnDetail()
        ;
        yield DateTimeField::new('createdAt')
            ->formatValue(fn(string $value, $entity) => $entity->getCreatedAt()->format('H:i:s d.m.Y'))
            ->onlyOnDetail()
        ;
    }

}

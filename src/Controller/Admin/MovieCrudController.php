<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\MovieScreenImageType;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    CollectionField,
    DateTimeField,
    IdField,
    ImageField,
    TextField,
    TextEditorField,
    IntegerField,
    DateField
};
use Vich\UploaderBundle\Form\Type\VichImageType;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural('Movies')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('title');
        if (Crud::PAGE_NEW !== $pageName)
            yield TextField::new('slug');
        yield TextEditorField::new('description');
        yield AssociationField::new('categories')
            ->autocomplete()
            ->formatValue(
                fn(string $value, Movie $entity) => implode(', ', $entity->getCategories()->toArray())
            )
        ;
        yield IntegerField::new('length');
        yield DateField::new('premiere_date')
            ->setFormat('d.M.y');
        yield ImageField::new('poster')
            ->setBasePath('/images/posters')
            ->hideOnForm();
        yield ImageField::new('posterImage')
            ->setFormType(VichImageType::class)
            ->setFormTypeOption('delete_label', 'Remove image?')
            ->onlyOnForms()
        ;
        yield CollectionField::new('images')
            ->setEntryType(MovieScreenImageType::class)
            ->setFormTypeOption('by_reference', false)
//            ->onlyOnForms()
        ;
        yield DateTimeField::new('updatedAt')
            ->formatValue(fn(string $value, $entity) => $entity->getUpdatedAt()->format('H:i:s d.m.Y'))
            ->onlyOnDetail()
        ;
        yield DateTimeField::new('createdAt')
            ->formatValue(fn(string $value, $entity) => $entity->getCreatedAt()->format('H:i:s d.m.Y'))
            ->onlyOnDetail()
        ;

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\MovieCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MovieCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MovieCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Category')
            ->setEntityLabelInPlural('Categories')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        if (Crud::PAGE_NEW !== $pageName)
            yield TextField::new('slug');
        yield ImageField::new('image')
            ->setBasePath('/images/movie-category')
            ->hideOnForm();
        yield ImageField::new('imageFile')
            ->setFormType(VichImageType::class)
            ->setFormTypeOption('delete_label', 'Remove image?')
            ->onlyOnForms()
        ;

//            TextEditorField::new('description'),
//        return [
//            ,
//        ];
    }
}

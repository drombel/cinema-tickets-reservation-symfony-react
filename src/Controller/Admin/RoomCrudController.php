<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\Room;
use App\Entity\Screening;
use App\Entity\Seat;
use App\Form\SeatType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\PaginatorFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function autocomplete(AdminContext $context): JsonResponse
    {
        $queryBuilder = $this
            ->createIndexQueryBuilder(
                $context->getSearch(), $context->getEntity(), FieldCollection::new([]), FilterCollection::new()
            );

        $screeningId = (int)$context->getRequest()->get('screening');
        if ($screeningId){
            $queryBuilder = $queryBuilder
                ->leftJoin(Screening::class, 's', 'WITH', 'entity.cinema = c')
                ->leftJoin(Cinema::class, 'c', 'WITH', 'c = s.cinema')
                ->where('s.id = :screeningId')
                ->setParameter('screeningId', $screeningId)
            ;
        }

        $paginator = $this->get(PaginatorFactory::class)->create($queryBuilder);
        return JsonResponse::fromJsonString($paginator->getResultsAsJson());
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield AssociationField::new('cinema')->autocomplete();
        yield IntegerField::new('cols')
            ->addCssClass('room-cols')
        ;
        yield IntegerField::new('rows')
            ->addCssClass('room-rows')
        ;

        if (\in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL]))
            yield CollectionField::new('seats')
                ->formatValue(function ($value, Room $room) {

                    return implode(" ", [
                        ("Total: ".$room->getSeats()->count()),
                        ("Active: ".$room->getSeats()->filter(fn (Seat $seat) => $seat->getStatus() === Seat::STATUS_ACTIVE )->count()),
                        ("Disabled: ".$room->getSeats()->filter(fn (Seat $seat) => $seat->getStatus() === Seat::STATUS_DISABLED )->count()),
//                        ("Hidden: ".$room->getSeats()->filter(fn (Seat $seat) => $seat->getStatus() === Seat::STATUS_HIDDEN )->count()),
                    ]);
                });
        else
            yield CollectionField::new('seats')
                ->setEntryType(SeatType::class)
                ->setFormTypeOption('by_reference', false)
                ->addCssFiles('bundles/easyadmin/css/room.seats.css')
                ->addJsFiles('bundles/easyadmin/js/room.seats.js')
                ->addCssClass('seat-collection')
                ->onlyOnForms()
            ;
    }

}

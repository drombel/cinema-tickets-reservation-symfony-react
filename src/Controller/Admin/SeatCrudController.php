<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\Screening;
use App\Entity\Seat;
use App\Entity\Showing;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\PaginatorFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Seat::class;
    }

    public function autocomplete(AdminContext $context): JsonResponse
    {
        $queryBuilder = $this
            ->createIndexQueryBuilder(
                $context->getSearch(), $context->getEntity(), FieldCollection::new([]), FilterCollection::new()
            );

        $showingId = (int)$context->getRequest()->get('showing');
        if ($showingId){
            $queryBuilder = $queryBuilder
                ->join(Room::class, 'r', 'WITH', 'r = entity.room')
                ->join(Showing::class, 's')
                ->where('s.id = :showingId')
                ->setParameter('showingId', $showingId)
            ;
        }

        $paginator = $this->get(PaginatorFactory::class)->create($queryBuilder);
        return JsonResponse::fromJsonString($paginator->getResultsAsJson());
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

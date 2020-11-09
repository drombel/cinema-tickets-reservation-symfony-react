<?php

namespace App\Repository;

use App\Entity\Screening;
use App\Entity\Showing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Showing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Showing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Showing[]    findAll()
 * @method Showing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Showing::class);
    }

    public function checkForShowingsAtSameTime(Showing $showing)
    {
        $qb = $this->createQueryBuilder('s');
        return $qb
            ->leftJoin(Screening::class, 'r', 'WITH', 'r = s.screening')
            ->andWhere(
                // same room
                $qb->expr()->eq('s.room', ':room'),
                // exclude current showing
                $qb->expr()->neq('s.id', ':id'),
                // date conditions
                $qb->expr()->orX(
                    $qb->expr()->between(':dateStart', 'r.dateStart', 'r.dateEnd'),
                    $qb->expr()->between(':dateEnd', 'r.dateStart', 'r.dateEnd'),
                    $qb->expr()->andX(
                        $qb->expr()->lte(':dateStart','r.dateStart'),
                        $qb->expr()->lte('r.dateEnd',':dateEnd'),
                    ),
                ),
                // time conditions
                $qb->expr()->orX(
                    // timeStart < timeEnd
                    $qb->expr()->andX(
                        $qb->expr()->gt(':timeStart', ':timeEnd'),
                        $qb->expr()->orX(
                            $qb->expr()->andX(
                                $qb->expr()->lt(':timeStart', 's.timeStart'),
                                $qb->expr()->lt('s.timeStart', ':timeEnd'),
                            ),
                            $qb->expr()->andX(
                                $qb->expr()->lt(':timeStart', 's.timeEnd'),
                                $qb->expr()->lt('s.timeEnd', ':timeEnd'),
                            ),
//                            $qb->expr()->between('s.timeStart', ':timeStart', ':timeEnd'),
//                            $qb->expr()->between('s.timeEnd', ':timeStart', ':timeEnd'),
                        ),
                    ),
                    // timeEnd < timeStart
                    $qb->expr()->andX(
                        $qb->expr()->gt(':timeEnd', ':timeStart'),
                        $qb->expr()->orX(
                            $qb->expr()->not(
                                $qb->expr()->andX(
                                    $qb->expr()->lt(':timeEnd', 's.timeStart'),
                                    $qb->expr()->lt('s.timeStart', ':timeStart'),
                                ),
//                                $qb->expr()->between('s.timeStart', ':timeEnd', ':timeStart')
                            ),
                            $qb->expr()->not(
                                $qb->expr()->andX(
                                    $qb->expr()->lt(':timeEnd', 's.timeEnd'),
                                    $qb->expr()->lt('s.timeEnd', ':timeStart'),
                                ),
//                                $qb->expr()->between('s.timeEnd', ':timeEnd', ':timeStart')
                            ),
                        ),
                    ),
                    // timeStart <= s.timeStart && s.timeEnd <= timeEnd
                    $qb->expr()->andX(
                        $qb->expr()->lte(':timeStart','s.timeStart'),
                        $qb->expr()->lte('s.timeEnd',':timeEnd'),
                    ),
                    // s.timeStart <= timeStart && timeEnd <= s.timeEnd
                    $qb->expr()->andX(
                        $qb->expr()->lte('s.timeStart', ':timeStart'),
                        $qb->expr()->lte(':timeEnd', 's.timeEnd'),
                    ),
                ),
            )
            ->setParameters([
                'id' => $showing->getId(),
                'room' => $showing->getRoom()->getId(),
                'dateStart' => $showing->getScreening()->getDateStart(),
                'dateEnd' => $showing->getScreening()->getDateEnd(),
                // type time needs to be formatted
                'timeStart' => $showing->getTimeStart()->format("H:i:s"),
                // type time needs to be formatted
                'timeEnd' => $showing->getTimeEnd()->format("H:i:s"),
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Showing
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

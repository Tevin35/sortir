<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\State;
use App\Entity\Trip;
use App\Form\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Trip[] Returns an array of Trip objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    /**
     * Get the trips link with the search options check or type in fields
     */
    public function findSearch(SearchData $searchData): array
    {
        $query = $this
            ->createQueryBuilder('t')
            ->select('t');

        if (!empty($searchData->getCampus())) {
            $query = $query
                ->andWhere('t.campus IN (:campus)')
                ->setParameter('campus', $searchData->getCampus());
        }
        if (!empty($searchData->getSearch())) {
            $query = $query
                ->andWhere('t.name LIKE :search')
                ->setParameter('search', "%{$searchData->getSearch()}%");
        }

        if (!empty($searchData->getStartingDate())) {
            $query = $query
                ->andWhere('t.dateStartHour >= :date_start')
                ->setParameter('date_start', $searchData->getStartingDate()->format('Y-m-d 00:00:00'));
        }

        if (!empty($searchData->getEndingDate())) {
            $query = $query
                ->andWhere('t.dateStartHour <= :date_end')
                ->setParameter('date_end', $searchData->getEndingDate()->format('Y-m-d 23:59:59'));
        }


        return $query->getQuery()->getResult();
    }
}

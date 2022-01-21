<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function searchByTerm($term){
        $queryBuilder = $this->createQueryBuilder('car');
        $query = $queryBuilder
            ->select('car')
            ->leftJoin('car.brand', 'brand')
            ->leftJoin('car.groupe', 'groupe')
            ->where('car.name LIKE :term')
            ->orwhere('car.year LIKE :term')
            ->orwhere('car.engine LIKE :term')
            ->orwhere('car.description LIKE :term')
            ->orwhere('car.brand.name LIKE :term')
            ->orwhere('car.brand.country LIKE :term')
            ->orwhere('car.groupe.name LIKE :term')
            ->orwhere('car.groupe.country LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Car[] Returns an array of Car objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Car
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

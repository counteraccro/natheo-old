<?php

namespace App\Repository\Admin;

use App\Entity\Admin\RouteRight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RouteRight|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteRight|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteRight[]    findAll()
 * @method RouteRight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteRight::class);
    }

    // /**
    //  * @return RouteRight[] Returns an array of RouteRight objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RouteRight
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

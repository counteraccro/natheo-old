<?php

namespace App\Repository\Admin;

use App\Entity\Admin\DataSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSystem[]    findAll()
 * @method DataSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSystem::class);
    }

    // /**
    //  * @return DataSystem[] Returns an array of DataSystem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataSystem
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

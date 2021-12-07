<?php

namespace App\Repository\Admin\Page;

use App\Entity\Admin\Page\PageTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageTag[]    findAll()
 * @method PageTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageTag::class);
    }

    // /**
    //  * @return PageTag[] Returns an array of PageTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PageTag
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

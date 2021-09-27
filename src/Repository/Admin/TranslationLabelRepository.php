<?php

namespace App\Repository\Admin;

use App\Entity\Admin\TranslationLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TranslationLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationLabel[]    findAll()
 * @method TranslationLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationLabel::class);
    }

    // /**
    //  * @return TranslationLabel[] Returns an array of TranslationLabel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TranslationLabel
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

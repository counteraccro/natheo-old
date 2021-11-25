<?php

namespace App\Repository\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FaqQuestionAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqQuestionAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqQuestionAnswer[]    findAll()
 * @method FaqQuestionAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqQuestionAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqQuestionAnswer::class);
    }

    // /**
    //  * @return FaqQuestionAnswer[] Returns an array of FaqQuestionAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FaqQuestionAnswer
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

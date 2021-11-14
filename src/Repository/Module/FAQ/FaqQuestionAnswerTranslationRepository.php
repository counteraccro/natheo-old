<?php

namespace App\Repository\Module\FAQ;

use App\Entity\Module\FAQ\FaqQuestionAnswerTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FaqQuestionAnswerTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqQuestionAnswerTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqQuestionAnswerTranslation[]    findAll()
 * @method FaqQuestionAnswerTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqQuestionAnswerTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqQuestionAnswerTranslation::class);
    }

    // /**
    //  * @return FaqQuestionAnswerTranslation[] Returns an array of FaqQuestionAnswerTranslation objects
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
    public function findOneBySomeField($value): ?FaqQuestionAnswerTranslation
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

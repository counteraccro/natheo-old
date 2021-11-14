<?php

namespace App\Repository\Module\FAQ;

use App\Entity\Module\FAQ\FaqQuestionAnswerTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FaqQuestionAnswerTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqQuestionAnswerTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqQuestionAnswerTag[]    findAll()
 * @method FaqQuestionAnswerTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqQuestionAnswerTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqQuestionAnswerTag::class);
    }

    // /**
    //  * @return FaqQuestionAnswerTag[] Returns an array of FaqQuestionAnswerTag objects
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
    public function findOneBySomeField($value): ?FaqQuestionAnswerTag
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

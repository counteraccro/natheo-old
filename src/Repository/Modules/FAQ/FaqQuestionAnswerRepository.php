<?php

namespace App\Repository\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * Retourne une liste de faq questions / réponses paginée
     * @param int $current_page
     * @param int $limit
     * @param array|null $filter
     * @return Paginator
     */
    public function listePaginate(int $current_page, int $limit, array $filter = null): Paginator
    {
        $dql = $this->createQueryBuilder('faqqr')
            ->join('faqqr.faqQuestionAnswerTranslations', 'faqqrt')
            ->join('faqqr.faqCategory', 'faqc')
            ->join('faqc.faqCategoryTranslations', 'faqct')
            ->addOrderBy('faqc.position')
            ->addOrderBy('faqqr.position');

        if($filter != null || !empty($filter))
        {
            if($filter['field'] == 'all')
            {
                $dql->where('faqqrt.question LIKE :question' )
                    ->setParameter('question', '%' . $filter['value'] . '%')
                    ->orWhere('faqqrt.answer LIKE :answer' )
                    ->setParameter('answer', '%' . $filter['value'] . '%')
                    ->orWhere('faqct.title LIKE :title' )
                    ->setParameter('title', '%' . $filter['value'] . '%')
                    /*->orWhere('r.label LIKE :label' )
                    ->setParameter('label', '%' . $filter['value'] . '%');*/;
            }
            else {

                if($filter['field'] == 'title')
                {
                    $dql->where('faqct.' . $filter['field'] . ' LIKE :value' )
                        ->setParameter('value', '%' . $filter['value'] . '%');
                }
                else {
                    $dql->where('faqqrt.' . $filter['field'] . ' LIKE :value' )
                        ->setParameter('value', '%' . $filter['value'] . '%');
                }

            }
        }

        $dql->getQuery();

        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($current_page - 1))
            ->setMaxResults($limit);
        return $paginator;
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

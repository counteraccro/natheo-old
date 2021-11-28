<?php

namespace App\Repository\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FaqCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqCategory[]    findAll()
 * @method FaqCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqCategory::class);
    }

    /**
     * Retourne une liste de faq catégorie paginée
     * @param int $current_page
     * @param int $limit
     * @param array|null $filter
     * @return Paginator
     */
    public function listeFaqCategoryPaginate(int $current_page, int $limit, array $filter = null): Paginator
    {
        $dql = $this->createQueryBuilder('faqc')
            ->join('faqc.faqCategoryTranslations', 'faqct')
            ->orderBy('faqc.position');

        if($filter != null || !empty($filter))
        {
            if($filter['field'] == 'all')
            {
                $dql->where('faqct.title LIKE :title' )
                    ->setParameter('title', '%' . $filter['value'] . '%')
                    ->orWhere('faqct.description LIKE :description' )
                    ->setParameter('description', '%' . $filter['value'] . '%')
                    /*->orWhere('r.label LIKE :label' )
                    ->setParameter('label', '%' . $filter['value'] . '%');*/;
            }
            else {
                $dql->where('faqct.' . $filter['field'] . ' LIKE :value' )
                    ->setParameter('value', '%' . $filter['value'] . '%');
            }
        }

        $dql->getQuery();

        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($current_page - 1))
            ->setMaxResults($limit);
        return $paginator;
    }

    /**
     * Retourne la liste de position sous la forme d'un tableau
     * @param string $local
     * @return array
     */
    public function getListeOrder(string $local, string $txt): array
    {
        $tab = $this->createQueryBuilder('faqc')
            ->join('faqc.faqCategoryTranslations', 'faqct')
            ->select('faqc.position', 'faqct.title')
            ->where('faqct.language = :language')
            ->setParameter('language', $local)
            ->orderBy('faqc.position', 'ASC')
            ->getQuery()
            ->getResult();

        $return = [];
        foreach ($tab as $item) {
            $return[$item['position'] . ' -> ' . $txt . ' ' . $item['title']] = $item['position'];
        }
        return $return;
    }

    // /**
    //  * @return FaqCategory[] Returns an array of FaqCategory objects
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
    public function findOneBySomeField($value): ?FaqCategory
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

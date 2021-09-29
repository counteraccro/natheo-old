<?php

namespace App\Repository\Admin;

use App\Entity\Admin\TranslationKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TranslationKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationKey[]    findAll()
 * @method TranslationKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationKey::class);
    }

    /**
     * Retourne une liste de translationKey paginée en fonction du filtre de recherche
     * @param int $current_page
     * @param int $limit
     * @param array $filter
     * @return Paginator
     */
    public function listeRoutePaginate(int $current_page, int $limit, array $filter): Paginator
    {
        $dql = $this->createQueryBuilder('tk')
            ->join('tk.translationLabels', 'tl');

        if($filter['application'] != '')
        {
            $dql->andWhere('tk.application = :application')
                ->setParameter('application', $filter['application']);
        }

        if($filter['module'] != '')
        {
            $dql->andWhere('tk.module = :module')
                ->setParameter('module', $filter['module']);
        }

        if($filter['key'] != '')
        {
            $dql->andWhere('tk.name LIKE :name')
                ->setParameter('name', '%' . $filter['key'] . '%');
        }

        if($filter['label'] != '')
        {
            $dql->andWhere('tl.label LIKE :label')
                ->setParameter('label', '%' . $filter['label'] . '%');
        }

        $dql->orderBy('tk.id')->getQuery();
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($current_page - 1))
            ->setMaxResults($limit);
        return $paginator;
    }


    /**
     * Retourne une liste d'application
     * @return array
     */
    public function listeApplications(): array
    {
        return $this->createQueryBuilder('tk')
            ->select('tk.application')
            ->groupBy('tk.application')
            ->orderBy('tk.application', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne une liste de module
     * @return array
     */
    public function listeModules(): array
    {
        return $this->createQueryBuilder('tk')
            ->select('tk.module')
            ->groupBy('tk.module')
            ->orderBy('tk.module', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return TranslationKey[] Returns an array of TranslationKey objects
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
    public function findOneBySomeField($value): ?TranslationKey
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

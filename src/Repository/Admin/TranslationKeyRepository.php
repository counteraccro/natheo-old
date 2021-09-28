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
     * Retourne une liste de translationKey paginée
     * @param $current_page
     * @param $limit
     * @return Paginator
     */
    public function listeRoutePaginate($current_page, $limit): Paginator
    {
        $dql = $this->createQueryBuilder('tk')
            ->orderBy('tk.id')
            ->getQuery();

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

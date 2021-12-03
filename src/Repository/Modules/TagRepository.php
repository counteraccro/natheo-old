<?php

namespace App\Repository\Modules;

use App\Entity\Modules\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Retourne une liste de tags paginée
     * @param int $current_page
     * @param int $limit
     * @param array|null $filter
     * @return Paginator
     */
    public function listeTagPaginate(int $current_page, int $limit, array $filter = null): Paginator
    {
        $dql = $this->createQueryBuilder('t')
            ->orderBy('t.id');

        if($filter != null || !empty($filter))
        {
            if($filter['field'] == 'all')
            {
                $dql->where('t.name LIKE :name' )
                    ->setParameter('name', '%' . $filter['value'] . '%')
                    /*->orWhere('r.shortLabel LIKE :sl' )
                    ->setParameter('sl', '%' . $filter['value'] . '%')
                    ->orWhere('r.label LIKE :label' )
                    ->setParameter('label', '%' . $filter['value'] . '%');*/;
            }
            else {
                $dql->where('t.' . $filter['field'] . ' LIKE :value' )
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
     * Renvoi une liste de tags en fonction de son nom
     * @param String $name
     * @return mixed
     */
    public function getByName(String $name): mixed
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name LIKE :val')
            ->setParameter('val', '%' . $name . '%')
            ->orderBy('t.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
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
    public function findOneBySomeField($value): ?Tag
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

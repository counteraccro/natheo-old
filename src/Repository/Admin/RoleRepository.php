<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * Retourne une liste de role paginée
     * @param int $current_page
     * @param int $limit
     * @param array|null $filter
     * @return Paginator
     */
    public function listeRolePaginate(int $current_page, int $limit, array $filter = null): Paginator
    {
        $dql = $this->createQueryBuilder('r')
            ->orderBy('r.id');

        if($filter != null || !empty($filter))
        {
            if($filter['field'] == 'all')
            {
                $dql->where('r.name LIKE :name' )
                    ->setParameter('name', '%' . $filter['value'] . '%')
                    ->orWhere('r.shortLabel LIKE :sl' )
                    ->setParameter('sl', '%' . $filter['value'] . '%')
                    ->orWhere('r.label LIKE :label' )
                    ->setParameter('label', '%' . $filter['value'] . '%');
            }
            else {
                $dql->where('r.' . $filter['field'] . ' LIKE :value' )
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

    // /**
    //  * @return Role[] Returns an array of Role objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Role
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

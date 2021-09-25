<?php
/**
 * Route repository
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Repository\Admin
 */

namespace App\Repository\Admin;

use App\Entity\Admin\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    /**
     * Met à jour à 1 la valeur is_depreciate pour toutes les routes
     * @return int|mixed|string
     */
    public function updateIsdepreciateAllRoute(): mixed
    {
        return $this->createQueryBuilder('r')
            ->update(Route::class, 'r')
            ->set('r.is_depreciate',':val')
            ->setParameter('val', 1)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * Retourne une liste de route paginée
     * @param $current_page
     * @param $limit
     * @return Paginator
     */
    public function listeRoutePaginate($current_page, $limit): Paginator
    {
        $dql = $this->createQueryBuilder('r')
            ->orderBy('r.id')
            ->getQuery();

        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($current_page - 1))
            ->setMaxResults($limit);
        return $paginator;
    }

    // /**
    //  * @return Route[] Returns an array of Route objects
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
    public function findOneBySomeField($value): ?Route
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
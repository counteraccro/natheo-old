<?php

namespace App\Repository\Modules\Menu;

use App\Entity\Modules\Menu\MenuElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuElement[]    findAll()
 * @method MenuElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuElement::class);
    }

    // /**
    //  * @return MenuElement[] Returns an array of MenuElement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuElement
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Retourne une liste de user paginée
     * @param int $current_page
     * @param int $limit
     * @param array|null $filter
     * @return Paginator
     */
    public function listeUserPaginate(int $current_page, int $limit, array $filter = null): Paginator
    {
        $dql = $this->createQueryBuilder('u')
            ->orderBy('u.id');

        if($filter != null || !empty($filter))
        {
            if($filter['field'] == 'all')
            {
                $dql->where('u.email LIKE :email' )
                    ->setParameter('email', '%' . $filter['value'] . '%')
                    /*->orWhere('r.shortLabel LIKE :sl' )
                    ->setParameter('sl', '%' . $filter['value'] . '%')
                    ->orWhere('r.label LIKE :label' )
                    ->setParameter('label', '%' . $filter['value'] . '%');*/;
            }
            else {
                $dql->where('u.' . $filter['field'] . ' LIKE :value' )
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
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

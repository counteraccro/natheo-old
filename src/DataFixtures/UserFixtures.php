<?php
/**
 * Fixture pour les users
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHarsher;

    /**
     * @param UserPasswordHasherInterface $passwordHarsher
     */
    public function __construct(UserPasswordHasherInterface $passwordHarsher)
    {
        $this->passwordHarsher = $passwordHarsher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword($this->passwordHarsher->hashPassword(
            $user,
            'admin@admin.com'
        ));
        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }
}

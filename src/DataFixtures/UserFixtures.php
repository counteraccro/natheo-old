<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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
}
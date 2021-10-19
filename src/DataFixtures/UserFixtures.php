<?php
/**
 * Fixture pour les users
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\DataFixtures
 */
namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Admin\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
        $default_avatar = 'default\img_avatar.png';

        // Compte root
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword($this->passwordHarsher->hashPassword(
            $user,
            'admin@admin.com'
        ));
        $user->setName(UserService::ROOT_NAME);
        $user->setPublicationName('Admin');
        $user->setSurname(UserService::ROOT_SURNAME);
        $user->setPasswordStrenght('ss');
        $user->addRolesCms($this->getReference(RoleFixtures::FIXTURE_ROLE_ROOT_REF));
        $user->setIsDisabled(false);
        $user->setAvatar($default_avatar);
        $manager->persist($user);
        $manager->flush();

        // Compte user delete
        $user = new User();
        $user->setEmail('john@doe.com');
        $user->setPassword($this->passwordHarsher->hashPassword(
            $user,
            'john@doe.com'
        ));
        $user->setName('John');
        $user->setPublicationName('John Doe');
        $user->setSurname('Doe');
        $user->setPasswordStrenght('<span class="badge bg-success">?</span>');
        $user->setIsDisabled(true);
        $user->setAvatar($default_avatar);
        $manager->persist($user);
        $manager->flush();

        // Compte admin
        $user = new User();
        $user->setEmail('counteraccro@gmail.com');
        $user->setPassword($this->passwordHarsher->hashPassword(
            $user,
            'counteraccro@gmail.com'
        ));
        $user->setName('Counter');
        $user->setPublicationName('Counteraccro');
        $user->setSurname('Accro');
        $user->setPasswordStrenght('<span class="badge bg-success">?</span>');
        $user->addRolesCms($this->getReference(RoleFixtures::FIXTURE_ROLE_ADM_REF));
        $user->setIsDisabled(false);
        $user->setAvatar($default_avatar);
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

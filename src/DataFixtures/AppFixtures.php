<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setLastName('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));

        $user1 = new User();
        $user1->setFirstName('Alice');
        $user1->setLastName('Smith');
        $user1->setEmail('alice@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, '123'));

        $user2 = new User();
        $user2->setFirstName('Bob');
        $user2->setLastName('Johnson');
        $user2->setEmail('bob@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, '456'));

        $manager->persist($admin);
        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}

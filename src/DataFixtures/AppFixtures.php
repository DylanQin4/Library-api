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
        // Chargement des données a partir de data.sql
        $this->loadDataFromSqlFile($manager, '/sql/data.sql');

        // Ajout de nouvelles donnees fixes
        $admin = new User();
        $admin->setLastName('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));

        $user1 = new User();
        $user1->setFirstName('Alice');
        $user1->setLastName('Smith');
        $user1->setEmail('alice@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, '123'));

        $manager->persist($admin);
        $manager->persist($user1);

        $manager->flush();
    }

    private function loadDataFromSqlFile(ObjectManager $manager, string $sqlFile): void
    {
        $projectRoot = realpath(__DIR__ . '/../../');
        $fullPath = $projectRoot . $sqlFile;

        if (file_exists($fullPath)) {
            $conn = $manager->getConnection();
            $sql = file_get_contents($fullPath);
            $conn->exec($sql);
        } else {
            throw new \RuntimeException("Le fichier SQL $fullPath n'existe pas.");
        }
    }
}

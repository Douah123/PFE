<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Job;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // Créer ou récupérer les catégories existantes
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            // Vous pouvez définir d'autres propriétés de catégorie si nécessaire
            $manager->persist($category);
            $categories[] = $category;
        }

        // Créer 10 jobs avec une catégorie et un fichier image fictifs
        
        for ($i = 0; $i < 10; $i++) {
            $job = new Job();
            $job->setTitle($faker->Title);
            $job->setType($faker->word);
            $job->setDescription($faker->paragraph);
            $job->setLocation($faker->city);
            $job->setSalary($faker->randomNumber(4) . ' TND/mois');
            $job->setCreatedAt(new \datetime);
            $job->setUpdatedAt(new \datetime);
            $date = new \DateTime();
            $date->add(new \DateInterval('P30D')); 
            $job->setExpiresAt($date); 

            // Assigner une catégorie au hasard
            $randomCategory = $categories[array_rand($categories)];
            $job->setCategory($randomCategory);

            // Définir un nom de fichier image fictif pour chaque job
            $imageName = 'image_' . ($i + 1) . '.jpg';
            $job->setImageName($imageName);
            // Vous pouvez également définir d'autres propriétés de l'image si nécessaire

            // Persist the job entity
            $manager->persist($job);
        }

        $admin = new User();
        $admin->setFirstName('Admin de JobFinder')
            ->setLastName('Douah')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setEmail('jobFinder@gmail.com')
            
            ->setPhoneNumber('+21692918610')
            ->setCountry('Tunisie')
            ->setRegion('Tunis')
            ->setAdress('Rastabia');
        $plainPassword = 'Password123'; 
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $plainPassword);
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

            // Création de 10 utilisateurs de test
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPhoneNumber($faker->phoneNumber);
            $user->setCountry($faker->country);
            $user->setRegion($faker->region);
            $user->setAdress($faker->address);
            $user->setRoles(['ROLE_USER']);

            // Générer un mot de passe sécurisé pour chaque utilisateur
            $plainPassword = 'Password123'; // Vous pouvez générer un mot de passe aléatoire si nécessaire
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

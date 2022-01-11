<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for($i = 1; $i <= 20; $i++){

            // Création d'un nouvel article
            $newArticle = new Article();
        
            // Hydratation du nouvel article
            $newArticle
                ->setTitle($faker->sentence) // Donnera article 1, article 2, etc...
                ->setContent($faker->text)
                ->setCreationDate( $faker->dateTime() )
                ->setPremium((bool)random_int(0, 1))
            ;
        
            // Enregistrement du nouvel article auprès de Doctrine
            $manager->persist($newArticle);
        
        }
        
        // Sauvegarde en BDD des articles
        $manager->flush();
    }
}

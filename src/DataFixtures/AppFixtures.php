<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * Utilisation du constructeur pour récupérer le service de hashage des mots de passe via autowiring
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }



    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 200; $i++) {

            // Création d'un nouvel article
            $newArticle = new Article();

            // Hydratation du nouvel article
            $newArticle
                ->setTitle($faker->catchPhrase) // Donnera article 1, article 2, etc...
                ->setContent($faker->text)
                ->setCreationDate($faker->dateTime())
                ->setPremium((bool)random_int(0, 1));
            if ($newArticle->getPremium() == 1) {
                $newArticle->setPrice(rand(1, 100) * 100);
            } else {
                $newArticle->setPrice(0);
            }

            // Enregistrement du nouvel article auprès de Doctrine
            $manager->persist($newArticle);
        }

        for ($i = 1; $i <= 200; $i++) {

            // Création d'un nouvel article
            $newUser = new User();

            // Hydratation du nouvel article
            $newUser
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($newUser, '&Secret8'))
                ->setName($faker->name)
                ->setFirstName($faker->firstName)
                ->setPremium((bool)random_int(0, 1));


            // Enregistrement du nouvel article auprès de Doctrine
            $manager->persist($newUser);
        }

        // Sauvegarde en BDD des articles
        $manager->flush();
    }
}

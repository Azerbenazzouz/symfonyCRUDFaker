<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        for ($j = 1; $j <= 3; $j++) {
            $cat = new Categorie();
            $libelle = $faker->name;
            $cat->setLibelle($libelle)
                ->setDescription($faker->text)
                ->setSlug(strtolower(preg_replace('/[^a-zA-Z0-9]/','-',$libelle)));
            $manager->persist($cat);
            for ($i = 1; $i <= 15; $i++) {
                $livre = new Livre();
                $titre = $faker->name;
                $livre->setTitre($titre)
                    ->setImage('https://picsum.photos/200')
                    ->setResume($faker->text)
                    ->setPrix($faker->numberBetween(10, 200))
                    ->setEditeur($faker->company)
                    ->setDateEdition(new \DateTime($faker->date()))
                    ->setCategorie($cat)
                    ->setIsbn($faker->isbn13())
                    ->setSlug(strtolower(preg_replace('/[^a-zA-Z0-9]/','-',$titre)))
                    ->setQte($faker->numberBetween(0,1000));
                $manager->persist($livre);
            }
        }
        $manager->flush();
    }
}

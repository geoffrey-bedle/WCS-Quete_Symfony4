<?php


namespace App\DataFixtures;

use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        /*
            $article = new Article();
            $article->setTitle('Framework PHP : Symfony 4');
            $article->setContent('Symfony 4, un framework sympa à connaitre !');
        */

        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->sentence));
            $article->setContent($faker->paragraph);
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . ($i + 1) % 5));
        }


        $manager->flush();
    }
}
<?php


namespace App\DataFixtures;

use Faker;
use App\Service\slugify;
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
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 1000; $i++) {
            $article = new Article();
            $slugify = new slugify();
            $article->setTitle(mb_strtolower($faker->sentence(6,true)));
            $article->setContent(mb_strtolower($faker->paragraph(3,true)));
            $article->setSlug($slugify->generate($article->getTitle()));
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . rand(0, 4)));
        }
        $manager->flush();
*/

for ($i = 1; $i <= 1000; $i++) {
        $category = new Category();
        $category->setName("category " . $i);
        $manager->persist($category);

        $tag = new Tag();
        $tag->setName("tag " . $i);
        $manager->persist($tag);

        $article = new Article();
        $article->setTitle("article " . $i);
        $article->setSlug($this->slugify>generate($article->getTitle()));
        $article->setContent("article " . $i . " content");
        $article->setCategory($category);
        $article->addTag($tag);
        $manager->persist($article);
    }

        $manager->flush();


    }
}
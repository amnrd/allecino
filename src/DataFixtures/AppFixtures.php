<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $faker = \Faker\Factory::create('fr_FR');

      // films
      for ($i = 0; $i <= 10; $i++)
      {
          $film = new Film();

          $content = '<p>' .join($faker->paragraphs(5), '</p><p>') .'</p>';

          $film->setName($faker->name)
               ->setImage($faker->imageUrl($width = 250, $height = 350))
               ->setDescription($content);

          $manager->persist($film);

          // commentaires
          for($j = 1; $j<= mt_rand(1,3); $j++)
          {

            $content = '<p>' .join($faker->paragraphs(5), '</p><p>') .'</p>';

            $comment = new Comment();
            $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setFilm($film);

            $manager->persist($comment);
          }
      }

        $manager->flush();
    }

}

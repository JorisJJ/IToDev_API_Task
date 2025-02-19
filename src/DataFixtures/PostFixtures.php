<?php
namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6));
            $post->setContent($faker->paragraphs(3, true));

            $manager->persist($post);
        }

        $manager->flush();
    }
}

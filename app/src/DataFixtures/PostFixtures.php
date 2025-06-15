<?php

/**
 * Post fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class PostFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class PostFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(100, 'post', function (int $i) {
            $post = new Post();
            $post->setTitle($this->faker->sentence);
            $post->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $post->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $category = $this->getRandomReference('category', Category::class);
            $post->setCategory($category);

            for ($a = 0; $a < 3; ++$a) {
                /** @var Tag $tag */
                $tag = $this->getRandomReference('tags', Tag::class);
                $post->addTag($tag);
            }

            $post->setContent($this->faker->paragraph(30));

            return $post;
        });
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}


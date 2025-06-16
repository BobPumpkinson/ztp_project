<?php

/**
 * Comment controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CommentControllerTest.
 */
class CommentControllerTest extends WebTestCase
{
    /**
     * Test '/comment' route.
     */
    public function testCommentRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/comment');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/comment/{id}' route.
     */
    public function testCommentViewRoute(): void
    {
        // given
        $client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $category = new Category();
        $category->setTitle('Test Category');
        $entityManager->persist($category);

        $tag = new Tag();
        $tag->setTitle('Test Tag');
        $entityManager->persist($tag);

        $post = new Post();
        $post->setTitle('Test Post');
        $post->setContent('This is a test post.');
        $post->setCategory($category);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdatedAt(new \DateTimeImmutable());
        $post->addTag($tag);
        $entityManager->persist($post);
        $entityManager->flush();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['email' => 'admin0@example.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin0@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($adminUser);
        }

        $comment = new Comment();
        $post->addComment($comment);
        $comment->setAuthor($adminUser);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);

        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/comment/'.$comment->getId());
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('Test comment content', $response->getContent());
    }
}

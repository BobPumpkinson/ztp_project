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
        $comment->setPost($post);
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

    /**
     * Test '/comment/create' route.
     */
    public function testCommentCreateRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = $userRepository->findOneBy(['email' => 'user0@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('user0@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($user);

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

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($user);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/comment/create/'.$post->getId());
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/comment/{id}/edit' route.
     */
    public function testCommentEditRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = $userRepository->findOneBy(['email' => 'user0@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('user0@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($user);

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

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($user);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/comment/'.$comment->getId().'/edit');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/comment/{id}/delete' route.
     */
    public function testCommentDeleteRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = $userRepository->findOneBy(['email' => 'user0@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('user0@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($user);

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

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($user);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/comment/'.$comment->getId().'/delete');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

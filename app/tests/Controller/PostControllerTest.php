<?php

/**
 * Post controller tests.
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
 * Class PostControllerTest.
 */
class PostControllerTest extends WebTestCase
{
    /**
     * Test '/post' route.
     */
    public function testPostRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/post');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/post/{id}' route.
     */
    public function testPostViewRoute(): void
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
        $client->request('GET', '/post/'.$post->getId());
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);

        $this->assertStringContainsString('Test Post', $response->getContent());
        $this->assertStringContainsString('This is a test post.', $response->getContent());
        $this->assertStringContainsString('Test Category', $response->getContent());
        $this->assertStringContainsString('Test Tag', $response->getContent());
        $this->assertStringContainsString('Test comment content', $response->getContent());
    }

    /**
     * Test '/post/create' route.
     */
    public function testPostCreateRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $adminUser = $userRepository->findOneBy(['email' => 'admin0@example.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin0@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($adminUser);
            $entityManager->flush();
        }

        $client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/post/create');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/post/{id}/edit' route.
     */
    public function testPostEditRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $adminUser = $userRepository->findOneBy(['email' => 'admin0@example.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin0@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($adminUser);
            $entityManager->flush();
        }

        $client->loginUser($adminUser);
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
        $post->addComment($comment);
        $comment->setAuthor($adminUser);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/post/'.$post->getId().'/edit');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/post/{id}/delete' route.
     */
    public function testPostDeleteRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $adminUser = $userRepository->findOneBy(['email' => 'admin0@example.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin0@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($adminUser);
            $entityManager->flush();
        }

        $client->loginUser($adminUser);
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
        $post->addComment($comment);
        $comment->setAuthor($adminUser);
        $comment->setContent('Test comment content');
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($comment);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/post/'.$post->getId().'/delete');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test removing tag.
     */
    public function testRemoveTag(): void
    {
        $tag = new Tag();
        $tag->setTitle('Test Tag');

        $post = new Post();
        $post->addTag($tag);
        $this->assertTrue($post->getTags()->contains($tag));

        $post->removeTag($tag);
        $this->assertFalse($post->getTags()->contains($tag));
    }

    /**
     * Test removing comment.
     */
    public function testRemoveComment(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);

        $comment = new Comment();
        $comment->setContent('Test comment');
        $comment->setAuthor($user);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());

        $post = new Post();
        $post->addComment($comment);
        $this->assertTrue($post->getComments()->contains($comment));

        $post->removeComment($comment);
        $this->assertFalse($post->getComments()->contains($comment));
    }
}

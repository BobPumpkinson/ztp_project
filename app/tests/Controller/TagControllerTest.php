<?php

/**
 * Tag controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TagControllerTest.
 */
class TagControllerTest extends WebTestCase
{
    /**
     * Test '/tag' route.
     */
    public function testTagRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/tag');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/tag/{id}' route.
     */
    public function testTagViewRoute(): void
    {
        // given
        $client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $tag = new Tag();
        $tag->setTitle('Test Tag');
        $tag->setCreatedAt(new \DateTimeImmutable());
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($tag);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/tag/'.$tag->getId());
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('Test Tag', $response->getContent());
    }

    /**
     * Test '/tag/create' route.
     */
    public function testTagCreateRoute(): void
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
        $client->request('GET', '/tag/create');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/tag/{id}/edit' route.
     */
    public function testTagEditRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $tag = new Tag();
        $tag->setTitle('Test Tag');
        $tag->setCreatedAt(new \DateTimeImmutable());
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($tag);
        $entityManager->flush();

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
        $client->request('GET', '/tag/'.$tag->getId().'/edit');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/tag/{id}/delete' route.
     */
    public function testTagDeleteRoute(): void
    {
        // given
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $tag = new Tag();
        $tag->setTitle('Test Tag');
        $tag->setCreatedAt(new \DateTimeImmutable());
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($tag);
        $entityManager->flush();

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
        $client->request('GET', '/tag/'.$tag->getId().'/delete');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

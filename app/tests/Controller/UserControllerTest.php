<?php

/**
 * User controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test '/user' route.
     */
    public function testUserRoute(): void
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
        $client->request('GET', '/user');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/user/{id}' route.
     */
    public function testUserViewRoute(): void
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
        $client->request('GET', '/user/'.$adminUser->getId());
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('admin0@example.com', $response->getContent());
    }

    /**
     * Test '/user/create' route.
     */
    public function testUserCreateRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/user/create');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/user/{id}/edit' route.
     */
    public function testUserEditRoute(): void
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

        $user = $userRepository->findOneBy(['email' => 'user0@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('user0@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/user/'.$user->getId().'/edit');
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('admin0@example.com', $response->getContent());
    }

    /**
     * Test '/user/{id}/delete' route.
     */
    public function testUserDeleteRoute(): void
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

        $user = $userRepository->findOneBy(['email' => 'user0@example.com']);
        if (!$user) {
            $user = new User();
            $user->setEmail('user0@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/user/'.$user->getId().'/delete');
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('admin0@example.com', $response->getContent());
    }
}

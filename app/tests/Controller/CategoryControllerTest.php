<?php

/**
 * Category controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    /**
     * Test '/category' route.
     */
    public function testCategoryRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/category');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test '/category/{id}' route.
     */
    public function testCategoryViewRoute(): void
    {
        // given
        $client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $category = new Category();
        $category->setTitle('Test Category');
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($category);
        $entityManager->flush();

        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/category/'.$category->getId());
        $response = $client->getResponse();
        $resultHttpStatusCode = $response->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
        $this->assertStringContainsString('Test Category', $response->getContent());
    }
}

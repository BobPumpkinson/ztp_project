<?php

/**
 * Tag controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Tag;
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
}

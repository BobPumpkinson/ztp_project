<?php

/**
 * Hello controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HelloControllerTest.
 */
class HelloControllerTest extends WebTestCase
{
    /**
     * Test '/hello' route.
     */
    public function testHelloRoute(): void
    {
        // given
        $client = static::createClient();
        $expectedStatusCode = 200;

        // when
        $client->request('GET', '/hello');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

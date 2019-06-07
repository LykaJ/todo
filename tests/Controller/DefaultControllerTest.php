<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('/GET', '/');

        static::assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }
}
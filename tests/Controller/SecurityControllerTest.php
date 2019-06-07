<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-06-07
 * Time: 13:23
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'JaneDoe';
        $form['_password'] = 'pass';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
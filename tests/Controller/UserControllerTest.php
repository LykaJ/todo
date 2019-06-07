<?php

namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class UserControllerTest extends WebTestCase
{
    private function logIn()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneByUsername('Admin');
        $session = $client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        return $client;
    }

    public function testCreateUserAction()
    {
        $client = $this->logIn();

        $crawler = $client->request('GET', '/users/create');

        $this->assertEquals(
            1,
            $crawler->filter('form')->count()
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'JaneDoe';
        $form['user[password][first]'] = 'pass';
        $form['user[password][second]'] = 'pass';
        $form['user[email]'] = 'jane@mail.com';
        $form['user[role]'] = 'ROLE_USER';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
    }
    public function testEditUserAction()
    {
        $client = $this->logIn();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->findOneByUsername('Admin');

        $crawler = $client->request('GET', '/users/'.$user->getId().'/edit');

        $this->assertEquals(1,	$crawler->filter('form')->count());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();

        $form['user_update[username]'] = 'Admin';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
    }
    public function testListUserAction()
    {
        $client = $this->logIn();
        $client->request('GET', '/users');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}
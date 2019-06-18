<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserControllerTest extends WebTestCase
{
    protected $encoder;
    private $entityManager;

    public function setUp()
    {
        $client = static::createClient();
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->encoder = $this->createMock(UserPasswordEncoderInterface::class);

    }

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

    private function logInAsUser()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneByUsername('JaneDoe');
        $session = $client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_USER'));
        $session->set('_security_' . $firewall, serialize($token));
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

        $form['user[username]'] = 'Nia';
        $form['user[password][first]'] = 'password_test';
        $form['user[password][second]'] = 'password_test';
        $form['user[email]'] = 'nia@email.fr';
        $form['user[role]'] = 'ROLE_USER';
        $client->submit($form);

        $client->followRedirect();

        $this->assertTrue($client->getResponse()->isRedirect('/users'));
        $this->assertContains("a bien été ajouté", $client->getResponse()->getContent());
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
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

        $form['user[username]'] = 'Admin';

        $client->submit($form);

        $client->followRedirect();

        $this->assertContains("a bien été modifié", $client->getResponse()->getContent());
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
    public function testListUserAction()
    {
        $client = $this->logIn();
        $client->request('GET', '/users');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testListUserFailAction()
    {
        $client = $this->logInAsUser();
        $client->request('GET', '/users');

        $this->assertContains('Access Denied', $client->getResponse()->getContent());
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

}
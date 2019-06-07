<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class UserControllerTest extends WebTestCase
{
    private $entityManager;

    public function setUp()
    {
        $client = static::createClient();
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    private function logIn(array $role)
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken('user', null, $firewallName, $role);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);
    }

    private function logInRealUser($userId)
    {
        $client = static::createClient();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($userId);
        $session = $client->getContainer()->get('session');
        $firewallName = 'main';
        $firewallContext = 'main';
        $token = new UsernamePasswordToken($user, null, $firewallName);

        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);
    }

    public function testListActionAccess()
    {
        $client = static::createClient();

        $this->logInRealUser(1);
        $client->request('GET', '/users');
        $client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testListError403()
    {
        $client = static::createClient();

        $this->logIn(['ROLE_USER']);
        $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testCreatePageAccess()
    {
        $client = static::createClient();

        $this->logInRealUser(1);
        $client->request('GET', '/users/create');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testCreatePageError403()
    {
        $client = static::createClient();

        $this->logIn(['ROLE_USER']);
        $client->request('GET', '/users/create');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $this->logInRealUser(1);

        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'New user';
        $form['user[password]'] = 'password_test';
        $form['user[email]'] = 'newuser@email.fr';
        $form['user[role]'] = 'ROLE_USER';
        $client->submit($form);

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains("L'utilisateur a bien été ajouté", $client->getResponse()->getContent());
    }

    public function testEditPageAccess()
    {
        $client = static::createClient();

        $this->logInRealUser(1);
        $client->request('GET', '/users/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }


    public function testEditActionForm()
    {
        $client = static::createClient();

        $this->logInRealUser(1);

        $crawler = $client->request('GET', '/users/3/edit');
        $form = $crawler->selectButton('Editer')->form();

        $form['user[username]'] = 'User edited';
        $form['user[password]'] = 'password_modif';
        $form['user[email]'] = 'edited_user@email.fr';
        $form['user[role]'] = 'ROLE_USER';

        $client->submit($form);
        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains("L'utilisateur a bien été modifié", $client->getResponse()->getContent());
    }
}
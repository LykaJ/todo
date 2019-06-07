<?php


namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;


class TaskControllerTest extends WebTestCase
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

    public function testList()
    {
        $client = static::createClient();
        $this->logIn(['ROLE_USER']);
        $client->request('GET', '/tasks');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
    public function testListDone()
    {
        $client = static::createClient();
        $this->logIn(['ROLE_USER']);
        $client->request('GET', '/tasks/done');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreatePage()
    {
        $client = static::createClient();
        $this->logIn(['ROLE_USER']);
        $client->request('GET', '/tasks/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionForm()
    {
        $client = static::createClient();
        $this->logIn(['ROLE_USER']);

        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Title new';
        $form['task[content]'] = 'Content new';
        $client->submit($form);

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a été bien été ajoutée", $client->getResponse()->getContent());
    }

    public function testEditActionOk()
    {
        $client = static::createClient();
        $task = new Task();

        $user = $this->logInRealUser(1);
        $crawler = $client->request('GET', '/tasks/1/edit');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        if ($user === $task->getUser())
        {
            $form = $crawler->selectButton('Modifier')->form();
            $form['task[title]'] = 'Titre édité';
            $form['task[content]'] = 'Contenu édité';
            $client->submit($form);

            $client->followRedirect();

            $this->assertSame(200, $client->getResponse()->getStatusCode());
            $this->assertContains("La tâche a bien été modifiée", $client->getResponse()->getContent());
        } else {
            $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        }

    }

    public function testDeleteActionOk()
    {
        $client = static::createClient();
        $task = new Task();

        $user = $this->logInRealUser(1);

        $client->request('/GET', '/tasks/1/delete');

        if ($user === $task->getUser() || $user !== 'anon.' )
        {
            $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
            $this->assertContains("La tâche a bien été supprimée", $client->getResponse()->getContent());
        } else {
            $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        }


    }

    public function testToggleOk()
    {
        $client = static::createClient();
        $this->logInRealUser(1);
        $client->request('GET', '/tasks/1/toggle');

        $client->followRedirect();


        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

}
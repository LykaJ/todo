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

    private function logIn()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneByUsername('Admin');
        $session = $client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        return $client;
    }

    public function testList()
    {

        $client = $this->logIn();

        $client->request('GET', '/tasks');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testListDone()
    {
        $client = $this->logIn();
        $client->request('GET', '/tasks/done');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreatePage()
    {
        $client = $this->logIn();
        $client->request('GET', '/tasks/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionForm()
    {
        $client = $this->logIn();

        $crawler = $client->request('GET', '/tasks/create');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        if ($client !== 'anon.') {
            $form = $crawler->selectButton('Ajouter')->form();
            $form['task[title]'] = 'Title new';
            $form['task[content]'] = 'Content new';

            $task = new Task();
            $task->setUser($user);
            $client->submit($form);

            $client->followRedirect();

            $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
            $this->assertContains("La tâche a été bien été ajoutée", $client->getResponse()->getContent());
        }


    }

    public function testEditActionOk()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $entityManager->getRepository(Task::class)->findOneBy([]);
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');

        $this->assertSame($task->getUser()->getId(), $user->getId());
        $this->assertEquals(1,	$crawler->filter('form')->count());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Title edited';
        $form['task[content]'] = 'Edited content';
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertTrue($client->getResponse()->isRedirect());

    }

    public function testDeleteActionOk()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $entityManager->getRepository(Task::class)->findOneBy([]);
        $user = $entityManager->getRepository(User::class)->findOneBy([]);
        $role = $user->getRole();

        $client->request('/GET', '/tasks/' . $task->getId() . '/delete');

        $this->assertSame('ROLE_ADMIN', $role);
        $this->assertEquals($task->getUser()->getId(), $user->getId());

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains("La tâche a bien été supprimée", $client->getResponse()->getContent());

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());


    }

    public function testToggleOk()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $task = $entityManager->getRepository(Task::class)->findOneBy([]);

        $client->request('GET', '/tasks/' .$task->getId(). '/toggle');
        $client->followRedirect();

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
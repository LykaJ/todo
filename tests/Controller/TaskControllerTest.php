<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-06-06
 * Time: 14:45
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class TaskControllerTest extends WebTestCase
{

    public function testTaskPageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        static::assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testCreateTask()
    {
        $client = static::createClient();

        $crawler = $client->request('/GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre de la tâche';
        $form['task[content]'] = 'Contenu de la tâche';
        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

}
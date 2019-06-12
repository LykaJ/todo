<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-06-06
 * Time: 13:20
 */

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


class TaskTest extends TestCase
{
    private $task;
    private $date;
    private $user;

    public function setUp()
    {
        $this->task = new Task();
        $this->date = new \DateTime();
        $this->user = new User();
    }

    public function testIsDoneDefault()
    {
        $flag = $this->task->isDone();

        $this->assertSame(false, $flag);
    }

    public function testCreatedAt()
    {
        $this->task->setCreatedAt($this->date);

        $this->assertSame($this->date, $this->task->getCreatedAt());
    }

    public function testUser()
    {
        $this->task->setUser($this->user);

        $this->assertSame($this->user, $this->task->getUser());
    }

    public function testToggle()
    {
        $this->task->toggle(true);

        $this->assertSame(true, $this->task->isDone());
    }

    public function testTitle()
    {
        $this->task->setTitle('Defqon 1');

        $this->assertSame('Defqon 1', $this->task->getTitle());
    }

    public function testContent()
    {
        $this->task->setContent('Task Content');

        $this->assertSame('Task Content', $this->task->getContent());
    }
}
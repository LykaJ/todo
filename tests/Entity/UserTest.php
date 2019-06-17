<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-06-06
 * Time: 14:20
 */

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    private $user;
    private $task;

    public function setUp()
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testUsername()
    {
        $this->user->setUsername('Jane');

        $this->assertSame('Jane', $this->user->getUsername());
    }

    public function testPassword()
    {
        $this->user->setPassword('pass');

        $this->assertSame('pass', $this->user->getPassword());
    }

    public function testEmail()
    {
        $this->user->setEmail('user@email.com');

        $this->assertSame('user@email.com', $this->user->getEmail());
    }

    public function testRole()
    {
        $this->user->setRole('ROLE_USER');

        $this->assertSame('ROLE_USER', $this->user->getRole());
    }

    public function testTasks()
    {
        $tasks = $this->user->getTasks($this->task->getUser());

        $this->assertSame($this->user->getTasks(), $tasks);
    }

    public function testRoles()
    {
        $roles = $this->user->getRoles($this->user->getRole());

        $this->assertSame(array($this->user->getRole()), $roles);
    }


    public function testSalt()
    {
        $this->assertEquals(null, $this->user->getSalt());
    }
    public function testEraseCredential()
    {
        static::assertSame(null, $this->user->eraseCredentials());
    }

}
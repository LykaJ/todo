<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-07-08
 * Time: 13:27
 */

namespace App\Tests\Voter;


use App\Entity\Task;
use App\Entity\User;
use App\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;


class TaskVoterTest extends TestCase
{
    private $task;
    private $user;
    private $voter;
    private $token;

    public function setUp()
    {
        $this->task = new Task();
        $this->voter = new TaskVoter();
        $this->user = $this->createMock(User::class);

        $this->token = new UsernamePasswordToken($this->user, 'admin', 'in_database');

    }

    public function testTaskDelete()
    {
        $this->user->method('getId')->willReturn(1);
        $this->task->setUser($this->user);
        $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    }

    public function testTaskDeleteAdmin()
    {
        $this->user->method('getRole')->willReturn('ROLE_ADMIN');
        $this->task->setUser(null);
        $this->assertSame(1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    }

    public function testTaskDeleteNotAuthor()
    {
        $this->user->method('getId')->willReturn(1);
        $taskUser = $this->createMock(User::class);
        $taskUser->method('getId')->willReturn(2);
        $this->task->setUser($taskUser);
        $this->assertSame(-1, $this->voter->vote($this->token, $this->task, ['DELETE']));
    }

    public function testTaskDeleteNoUser()
    {
        $token = new AnonymousToken('secret', 'anon');
        $this->assertSame(-1, $this->voter->vote($token, $this->task, ['DELETE']));
    }

}
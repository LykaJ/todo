<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-07-08
 * Time: 12:51
 */

namespace App\Voter;


use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    // these strings are just invented: you can use anything
    const DELETE = 'delete';


    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['DELETE', 'EDIT'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $task, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute)
        {
            case 'DELETE':
                if ($user === $task->getUser())
                {
                    return true;
                }

                if ($user->getRole() === 'ROLE_ADMIN' && $task->getUser() === null)
                {
                    return true;
                }
                break;
        }
        return false;
    }



}
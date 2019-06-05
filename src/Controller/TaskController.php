<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findAll()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, Security $security)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $user = $security->getToken()->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user !== 'anon.') {
                $task->setUser($user);
            }

            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request, Security $security)
    {
        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);
        $user = $security->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user === $task->getUser()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

            } else {
                $this->addFlash('error', 'Vous ne pouvez pas modifier cette tâche');
            }
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task, Security $security)
    {
        $user = $security->getToken()->getUser();

        if ($user !== 'anon.')
        {
            $currentRole = $user->getRole();

            if ($user === $task->getUser()|| $currentRole === 'ROLE_ADMIN') {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a bien été supprimée.');
            }
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer cette tâche');
        }

        return $this->redirectToRoute('task_list');
    }
}

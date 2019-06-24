<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{

    private $manager;
    private $repository;

    public function __construct(ObjectManager $manager, TaskRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        $tasks = $this->repository->findNotDone();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/done", name="task_done_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDoneAction()
    {
        $tasks = $this->repository->findDone();
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, Security $security)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $user = $security->getToken()->getUser();

        if ($user === 'anon.') {
            $this->addFlash('error', 'Connectez-vous pour ajouter une tâche');
            return $this->redirectToRoute('login');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);
            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée');
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
                $this->manager->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée');

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
        $this->manager->flush();

        if ($task->isDone())
        {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite', $task->getTitle()));

            return $this->redirectToRoute('task_done_list');
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée', $task->getTitle()));

            return $this->redirectToRoute('task_list');
        }

    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task, Security $security)
    {
        $user = $security->getToken()->getUser();

        if ($user !== 'anon.') {
            $currentRole = $user->getRole();

            if ($user === $task->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a bien été supprimée');
            } elseif ($currentRole === 'ROLE_ADMIN' && $task->getUser() === null) {

                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a bien été supprimée.');
            } else {
                $this->addFlash('error', 'Vous n\'êtes pas l\'auteur(e) de cette tâche');
            }
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer cette tâche');
        }

        return $this->redirectToRoute('task_list');
    }
}

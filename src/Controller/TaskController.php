<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title'])
             ->setDescription($data['description'])
             ->setStatus($data['status'])
             ->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime());

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task created successfully!'], Response::HTTP_CREATED);
    }

    #[Route('/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }
        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Task updated successfully!'], Response::HTTP_OK);
    }

    #[Route('/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task deleted successfully!'], Response::HTTP_OK);
    }
    public function findTasks(string $query = '', string $status = '', int $page = 1): Paginator {
    $qb = $this->createQueryBuilder('t');
    if ($query) {
        $qb->andWhere('t.title LIKE :query OR t.description LIKE :query')
           ->setParameter('query', "%$query%");
    }
    if ($status) {
        $qb->andWhere('t.status = :status')
           ->setParameter('status', $status);
    }

    return $this->paginate($qb, $page, 10);
    }

}


<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Task\Application\Model\TaskModel;
use App\Application\Task\Domain\TaskRepositoryInterface;
use App\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    )
    {
    }

    // стандартный метод секъюрити
    // #[IsGranted('ROLE_ADMIN')]
    // свой кастомный аргумент
    #[IsGranted(roles: ['ROLE_ADMIN'])]
    public function getTaskList(Request $request): JsonResponse
    {
        // я бы эту логику перенес в useCase
        $tasks = $this->taskRepository->findBy([]);

        $data = [];
        foreach ($tasks as $task) {
            $data[] = new TaskModel($task);
        }

        return $this->json($data);
    }
}

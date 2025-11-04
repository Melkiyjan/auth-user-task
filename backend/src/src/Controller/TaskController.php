<?php

namespace App\Controller;

use App\Component\Security\SecurityContext;
use App\Application\Task\Application\Model\TaskModel;
use App\Application\Task\Domain\TaskRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Attribute\IsGranted;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly SecurityContext $securityContext
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

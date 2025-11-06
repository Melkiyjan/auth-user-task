<?php

declare(strict_types=1);

namespace App\Action;

use App\Application\Task\Domain\TaskRepositoryInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/channels', name: 'channels_list', methods: ['GET'])]
final readonly class GetTaskListAction
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }
    public function __invoke(): array
    {
        // я бы сюда подключил пагинатор
        return $this->taskRepository->findBy([]);
    }
}

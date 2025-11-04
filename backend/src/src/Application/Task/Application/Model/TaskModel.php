<?php

namespace App\Application\Task\Application\Model;

use App\Application\Task\Domain\Entity\Task;

class TaskModel
{
    // тут группы сериализации
    public function __construct(private readonly Task $task)
    {
    }

    public function getId(): string
    {
        return (string) $this->task->getId();
    }

    public function getTitle(): string
    {
        return $this->task->getTitle();
    }

    public function getDescription(): string
    {
        return $this->task->getDescription();
    }
}

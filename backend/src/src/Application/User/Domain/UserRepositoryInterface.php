<?php

namespace App\Application\User\Domain;

use App\Application\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Exception\JsonException;

interface UserRepositoryInterface
{
    public const string FILTER_EMAIL = 'email';

    public function findById(string $id): ?User;

    /**
     * @throws JsonException
     */
    public function getById(string $id, ?string $msg = null): User;

    /**
     * @return User[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    public function findOneBy(array $criteria, ?array $orderBy = null): ?User;

    /**
     * @throws JsonException
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): User;

    public function save(User $user): void;

    public function remove(User $user): void;
}

<?php

declare(strict_types=1);

namespace App\Component\Security;

use App\Application\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class SecurityContext
{
    public function __construct(private RequestStack $requestStack) {}

    /**
     * @param string[] $payload
     */
    public function setUserData(array $payload): void
    {
        $this->requestStack->getCurrentRequest()?->attributes->set('_auth_user', $payload);
    }

    /**
     * @return string[]|null
     */
    public function getUser(): ?array
    {
        return $this->requestStack->getCurrentRequest()?->attributes->get('_auth_user');
    }

    /**
     * @param string[] $roles
     */
    public function isGranted(array $roles): bool
    {
        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        $userRole = $user['role'] ?? User::ROLE_USER;

        return in_array($userRole, $roles, true);
    }
}

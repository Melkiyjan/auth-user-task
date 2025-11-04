<?php
namespace App\Component\Security;

use Symfony\Component\HttpFoundation\RequestStack;

final class SecurityContext
{
    public function __construct(private readonly RequestStack $requestStack) {}

    public function setUserData(array $payload): void
    {
        $this->requestStack->getCurrentRequest()?->attributes->set('_auth_user', $payload);
    }

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
        $userRoles = (array)($user['roles'] ?? $user['role'] ?? []);

        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }
        return !array_diff($roles, $userRoles);
    }
}

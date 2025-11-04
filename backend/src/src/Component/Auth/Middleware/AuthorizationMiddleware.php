<?php

namespace App\Component\Auth\Middleware;

use App\Component\Auth\AuthService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): ?JsonResponse
    {
        if ($this->isPublicRoute($request)) {
            return null;
        }

        $token = $this->extractToken($request);

        if (!$token) {
            return new JsonResponse(['error' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        $userData = $this->authService->validateToken($token);

        if (!$userData) {
            return new JsonResponse(['error' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('user_data', $userData);

        return null;
    }

    private function extractToken(Request $request): ?string
    {
        $header = $request->get('Authorization');

        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function isPublicRoute(Request $request): bool
    {
        return in_array($request->getPathInfo(), ['/api/login', '/api/register']);
    }
}

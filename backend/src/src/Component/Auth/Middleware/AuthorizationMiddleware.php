<?php

declare(strict_types=1);

namespace App\Component\Auth\Middleware;

use App\Component\Auth\AuthService;
use App\Component\Security\SecurityContext;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly SecurityContext $securityContext,
        /** @var string[] */ private readonly array $publicRoutes = [],
    ){
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): ?Response
    {
        if ($this->isPublicRoute($request)) {
            return null;
        }

        $token = $this->extractBearerToken($request);
        if (!$token) {
            return $this->unauthorized('Missing bearer token');
        }

        $payload = $this->authService->validateToken($token);
        if ($payload === null) {
            return $this->unauthorized('Invalid or expired token');
        }

        $payload = json_decode(json_encode($payload, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        $this->securityContext->setUserData($payload);

        return null;
    }

    private function unauthorized(string $message): Response
    {
        return new JsonResponse(
            ['error' => $message],
            Response::HTTP_UNAUTHORIZED,
            ['WWW-Authenticate' => 'Bearer realm="api", error="invalid_token"']
        );
    }

    private function extractBearerToken(Request $request): ?string
    {
        $header = $request->headers->get('Authorization', '');
        if (preg_match('/^\s*Bearer\s+(.+)\s*$/i', (string) $header, $m)) {
            return $m[1];
        }

        $jwt = $request->cookies->get('jwt');

        return is_string($jwt) ? $jwt : null;
    }

    private function isPublicRoute(Request $request): bool
    {
        $path = $request->getPathInfo();
        foreach ($this->publicRoutes as $publicRoute) {
            $regex = '#^' . str_replace('\*', '.*', preg_quote($publicRoute, '#')) . '$#';
            if (preg_match($regex, $path)) {
                return true;
            }
        }

        return false;
    }
}

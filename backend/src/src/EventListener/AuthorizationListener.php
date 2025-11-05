<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use App\Component\Auth\Middleware\AuthorizationMiddleware;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AuthorizationListener
{
    public function __construct(private readonly AuthorizationMiddleware $authorizationMiddleware)
    {
    }

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!$requestEvent->isMainRequest()) {
            return;
        }

        $request = $requestEvent->getRequest();
        $response = $this->authorizationMiddleware->handle($request);

        if ($response instanceof Response) {
            $requestEvent->setResponse($response);
        }
    }
}

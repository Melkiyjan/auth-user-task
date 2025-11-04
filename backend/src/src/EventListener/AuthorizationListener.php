<?php

namespace App\EventListener;

use App\Component\Auth\Middleware\AuthorizationMiddleware;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationListener
{
    public function __construct(private readonly AuthorizationMiddleware $authMiddleware)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $this->authMiddleware->handle($request);

        if ($response instanceof Response) {
            $event->setResponse($response);
        }
    }
}

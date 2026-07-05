<?php

namespace App\EventSubscriber;

use App\Repository\KeyRepository;  // capital K here
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiKeySubscriber implements EventSubscriberInterface
{
    public function __construct(private KeyRepository $keyRepository) {}  // consistent name

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/_')) {
            return;
        }

        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            $event->setResponse(new JsonResponse(
                ['error' => 'Missing API key'],
                401
            ));
            return;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $apiKey = $this->keyRepository->findOneBy(['key' => $token]);  // matches constructor

        if (!$apiKey) {
            $event->setResponse(new JsonResponse(
                ['error' => 'Invalid API key'],
                401
            ));
            return;
        }

        $request->attributes->set('apiKey', $apiKey);
    }
}
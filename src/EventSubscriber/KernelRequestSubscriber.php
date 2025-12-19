<?php

// src/EventSubscriber/KernelRequestSubscriber.php
namespace Webgiciel2\InitBureauSecurite\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Webgiciel2\InitBureauSecurite\Service\SecuriteInitializer;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SecuriteInitializer $initializer
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->initializer->initializeIfNeeded();
    }
}

<?php

// src/EventSubscriber/KernelRequestSubscriber.php
namespace Webgiciel2\InitBureauSecurite\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webgiciel2\InitBureauSecurite\Service\SecuriteInitializer;

use Webgiciel2\InitBureauSecurite\Service\InitBureauSecuriteManager;


class KernelRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private InitBureauSecuriteManager $initManager,
        private SecuriteInitializer $initializer
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {

        // uniquement sur la requête principale
        if (!$event->isMainRequest()) {
            return;
        }

        $this->initManager->initialize();
    }
}

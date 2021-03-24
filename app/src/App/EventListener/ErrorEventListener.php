<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Http\Response\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ErrorEventListener implements EventSubscriberInterface
{
    private ResponseFactory $response;
    private LoggerInterface $logger;

    public function __construct(ResponseFactory $response, LoggerInterface $logger)
    {
        $this->response = $response;
        $this->logger = $logger;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $code = (int) $e->getCode();

        $this->logger->debug($e->getMessage(), ['exception' => $e]);

        if ($code) {
            /** @var JsonResponse $response */
            $response = $this->response->json([ 'message' => $e->getMessage() ], $code);
        } else {
            /** @var JsonResponse $response */
            $response = $this->response->json([ 'message' => $e->getMessage() ]);
        }

        $event->setResponse($response);
    }
}

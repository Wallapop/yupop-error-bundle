<?php

/*
 * This file is part of the shopery/error-bundle package.
 *
 * Copyright (c) 2015 Shopery.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopery\Bundle\ErrorBundle\Listener;

use Exception;
use Shopery\Bundle\ErrorBundle\Exception\DecoratedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class ExceptionListener
 *
 * @author Berny Cantos <be@rny.cc>
 */
class ExceptionListener
{
    /**
     * @var Exception[]
     */
    private array $exceptions;

    public function __construct(array $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof HttpException) {
            return;
        }

        $throwable = $this->decorateThrowable($throwable);
        if ($throwable) {
            $event->setThrowable($throwable);
        }
    }

    private function decorateThrowable(Throwable $throwable): ?DecoratedHttpException
    {
        foreach ($this->exceptions as $className => $settings) {
            if (is_a($throwable, $className)) {
                $code = $settings['code'];
                $exposeMessage = $settings['expose_message'] ?? false;
                /** @var string $message */
                $message = $exposeMessage === true ? $throwable->getMessage() : Response::$statusTexts[$code];

                return new DecoratedHttpException($code, $message, $throwable);
            }
        }

        return null;
    }
}

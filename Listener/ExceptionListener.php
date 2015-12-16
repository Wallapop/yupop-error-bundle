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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExceptionListener
 *
 * @author Berny Cantos <be@rny.cc>
 */
class ExceptionListener
{
    /**
     * @var array
     */
    private $exceptions;

    /**
     * @param array $exceptions
     */
    public function __construct(array $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof HttpException) {
            return;
        }

        foreach ($this->exceptions as $className => $settings) {
            if (is_a($exception, $className)) {

                $code = $settings['code'];
                $message = $settings['expose_message']
                    ? $exception->getMessage()
                    : Response::$statusTexts[$code];

                throw new HttpException($code, $message);
            }
        }
    }
}

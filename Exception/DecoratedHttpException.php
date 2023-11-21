<?php

namespace Shopery\Bundle\ErrorBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class ExceptionListener
 *
 * @author Roger Gros <roger@gros.cat>
 */
class DecoratedHttpException extends HttpException
{
    public function decorated(): ?Throwable
    {
        return $this->getPrevious();
    }
}

<?php

namespace Shopery\Bundle\ErrorBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExceptionListener
 *
 * @author Roger Gros <roger@gros.cat>
 */
class DecoratedHttpException extends HttpException
{
    /**
     * @return \Exception
     */
    public function decorated()
    {
        return $this->getPrevious();
    }
}

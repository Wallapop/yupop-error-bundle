<?php

/*
 * This file is part of the shopery/error-bundle package.
 *
 * Copyright (c) 2015 Shopery.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopery\Bundle\ErrorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ErrorBundle
 *
 * @author Berny Cantos <be@rny.cc>
 */
class ErrorBundle extends Bundle
{
    public function getContainerExtension()
    {
        return
            $this->extension ?:
            ($this->extension = new DependencyInjection\ErrorExtension());
    }
}

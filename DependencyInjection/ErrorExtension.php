<?php

/*
 * This file is part of the shopery/error-bundle package.
 *
 * Copyright (c) 2015 Shopery.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopery\Bundle\ErrorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ErrorExtension
 *
 * @author Berny Cantos <be@rny.cc>
 */
class ErrorExtension extends Extension
{
    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @api
     */
    public function getAlias(): string
    {
        return 'shopery_error';
    }

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $exceptions = $config['exceptions'];
        if (!empty($exceptions)) {
            $priority = $config['priority'];
            $this->setupExceptions($exceptions, $priority, $container);
        }
    }

    /**
     * Returns extension configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        $configuration = new Configuration($this->getAlias());
        $container->addObjectResource($configuration);

        return $configuration;
    }

    private function setupExceptions(array $exceptions, int $priority, ContainerBuilder $container): void
    {
        $this->loadResource($container, 'exceptions.yml');

        $definition = $container->getDefinition('shopery.error.exception_listener');
        $definition->replaceArgument(0, $exceptions);
        $definition->addTag('kernel.event_listener', [
            'event' => 'kernel.exception',
            'method' => 'onKernelException',
            'priority' => $priority,
        ]);
    }

    /**
     * @param mixed $resource
     */
    private function loadResource(ContainerBuilder $container, $resource): void
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $locator);

        $loader->load($resource);
    }
}

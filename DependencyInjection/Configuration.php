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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Berny Cantos <be@rny.cc>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @param string $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $this->generateConfiguration(
            $builder->root($this->alias)
        );

        return $builder;
    }

    /**
     * @param ArrayNodeDefinition $root
     */
    private function generateConfiguration(ArrayNodeDefinition $root)
    {
        $root
            ->fixXmlConfig('exception')
            ->children()
                ->arrayNode('exceptions')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->children()
                            ->scalarNode('code')->end()
                            ->booleanNode('expose_message')
                                ->defaultValue('%kernel.debug%')
                            ->end()
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(function ($v) {
                                return is_numeric($v);
                            })
                            ->then(function ($v) {
                                return [
                                    'code' => $v,
                                ];
                            })
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

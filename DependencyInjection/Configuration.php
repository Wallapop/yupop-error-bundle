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
    private string $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder($this->alias);

        $this->generateConfiguration(
            $builder->getRootNode()
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
                ->scalarNode('priority')
                    ->defaultValue(0)
                ->end()
            ->end()
        ;
    }
}

<?php

/*
 * This file is part of winzouCacheBundle.
 *
 * winzouCacheBundle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * winzouCacheBundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace winzou\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration builder for final dev app/config/config.yml
 * @author winzou
 */
class Configuration
{
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('winzou_cache');
        
        $rootNode
            ->children()
                ->arrayNode('factory')
                    ->children()
                        ->scalarNode('class')->defaultValue('winzou\\CacheBundle\\CacheFactory')->end()
                     ->end()
                 ->end()
                 ->arrayNode('driver')
                    ->children()
                        ->scalarNode('default')->defaultValue('File')->end()
                        ->scalarNode('abstract_class')->defaultValue('winzou\\CacheBundle\\Cache\\AbstractCache')->end()
                     ->end()
                ->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('cache_dir')->defaultValue('winzou_cache')->end()
                     ->end()
                ->end()
            ->end();
            
        return $treeBuilder->buildTree();
    }
}
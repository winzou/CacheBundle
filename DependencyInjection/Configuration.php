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
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('winzou\\CacheBundle\\CacheFactory')->end()
                    ->end()
                ->end()
                ->arrayNode('driver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('apc_class')     ->defaultValue('winzou\\CacheBundle\\Cache\\ApcCache')     ->end()
                        ->scalarNode('array_class')   ->defaultValue('winzou\\CacheBundle\\Cache\\ArrayCache')   ->end()
                        ->scalarNode('file_class')    ->defaultValue('winzou\\CacheBundle\\Cache\\FileCache')    ->end()
                        ->scalarNode('memcache_class')->defaultValue('winzou\\CacheBundle\\Cache\\MemcacheCache')->end()
                        ->scalarNode('xcache_class')  ->defaultValue('winzou\\CacheBundle\\Cache\\XcacheCache')  ->end()
                        ->scalarNode('zenddata_class')->defaultValue('winzou\\CacheBundle\\Cache\\ZendDataCache')->end()
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cache_dir')->defaultValue('winzou_cache')->end()
                        ->scalarNode('default_driver')->defaultValue('Array')->end()
                     ->end()
                ->end()
            ->end();
            
        return $treeBuilder->buildTree();
    }
}
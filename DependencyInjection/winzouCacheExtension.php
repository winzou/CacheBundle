<?php

/*
 * This file is part of winzouCacheBundle.
 *
 * winzouCacheBundle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * winzouBookBundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace winzou\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * Extension for the bundle winzouCacheExtension
 * @author winzou
 */
class winzouCacheExtension extends Extension
{
    /**
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);
        
        // if the cache_dir parameter is not defined, we force it to be ROOT/cache/ENV/winzou_cache
        if (!isset($config['options']['cache_dir'])) {
            $config['options']['cache_dir'] = $container->getParameter('kernel.root_dir').'/cache/'.$container->getParameter('kernel.environment').'/winzou_cache';
        }

        // we check if the default_driver value is ok
        if (!isset($config['driver'][strtolower($config['options']['default_driver'])])) {
            throw new \InvalidArgumentException('The parameter winzou_book.options.default_driver[value="'.$config['options']['default_driver'].'"] is invalid.');
        }
        $config['internal']['default_driver_class'] = $config['driver'][strtolower($config['options']['default_driver'])];
        
        $this->bindParameter($container, 'winzou_cache', $config);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
    
    /**
     * Set the given parameters to the given container
	 
     * @param ContainerBuilder $container
     * @param string $name
     * @param mixed $value
     */
    private function bindParameter(ContainerBuilder $container, $name, $value)
    {
        if (is_array($value)) {
            foreach ($value as $index => $val) {
                $this->bindParameter($container, $name.'.'.$index, $val);
            }
        } else {
            $container->setParameter($name, $value);
        }
    }
}
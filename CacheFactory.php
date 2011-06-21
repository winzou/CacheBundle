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

namespace winzou\CacheBundle;

use winzou\CacheBundle\Cache;

class CacheFactory
{
    /** @var array $drivers */
    private $drivers;
    
    /** @var array $options */
	private $options;
	
	/**
	 * Constructor.
	 *
	 * @param array $drivers The list of available drivers, key=driver name, value=driver class
	 * @param array $options Options to pass to the driver
	 */
	public function __construct(array $drivers, array $options = array())
	{
	    $this->drivers = $drivers;
		$this->options = $options;
	}
	
	/**
	 * Builder.
	 *
	 * @param string $driver The cache driver to use
	 * @param array $options Options to pass to the driver
	 * @param bool $byPassCheck If you want to perform a (slow) check, set false (but you should know in advance which driver is supporteed by your configuration)
	 * @return Cache\AbstractCache
	 */
	public function get($driver, array $options = array(), $byPassCheck = true)
	{
		if (!$this->driverExists($driver)) {
			throw new \InvalidArgumentException('The cache driver "'.$driver.'" is not supported by winzouCacheBundle.');
		}
		
		if (!$byPassCheck && !$this->driverSupported($driver)) {
			throw new \InvalidArgumentException('The cache driver "'.$driver.'" is not supported by your current configuration.');
		}
		
		$class = $this->drivers[$driver];
		
		if ($driver == 'file') {
			$options = array_merge($this->options, $options);
			
			if (!isset($options['cache_dir'])) {
				throw new \InvalidArgumentException('The parameter "cache_dir" must be defined when using the File driver.');
			}
			return new $class($options['cache_dir']);
		}
		
		if ($driver == 'memcache') {
			$options = array_merge($this->options, $options);
		
			if (!isset($options['memcache'])) {
				throw new \InvalidArgumentException('The parameter "memcache" must be defined when using the Memcache driver.');
			}
			return new $class($options['memcache']);
		}
		
		return new $class;
	}
	
	/**
	 * Check if the given driver is supported by the bundle
	 *
	 * @param string $driver
	 * @return bool
	 */
	public function driverExists($driver)
	{
		return isset($this->drivers);
	}
	
	/**
	 * Check if the given driver is supported by the current web server configuration
	 *
	 * @param string $driver
	 * @return bool
	 */
	public function driverSupported($driver)
	{
		switch($driver)
		{
			case 'file':
			case 'array':
				return true;
				break;
			
			case 'zendData':
				return function_exists('zend_shm_cache_fetch');
				break;
			
			case 'memcache':
				return class_exists('Memcache');
				break;
			
			case 'apc':
				return function_exists('apc_fetch');
				break;
			
			case 'xcache':
				return function_exists('xcache_get');
				break;
		}
		
		return false;
	}
	
}
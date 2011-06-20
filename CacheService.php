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
	private $options;
	
	/**
	 * Constructor.
	 *
	 * @param array $options Options to pass to the driver
	 */
	public function __construct(array $options = array())
	{
		$this->options = $options;
	}
	
	/**
	 * Builder.
	 *
	 * @param string $driver The cache driver to use
	 * @param array $options Options to pass to the driver
	 */
	public function build($driver, array $options = array(), $byPassCheck = true)
	{
		if (!$this->driverExists($driver)) {
			throw new \InvalidArgumentException('The cache driver "'.$driver.'" is not supported by winzouCacheBundle.');
		}
		
		if (!$byPassCheck && !$this->driverSupported($driver)) {
			throw new \InvalidArgumentException('The cache driver "'.$driver.'" is not supported by your current configuration.');
		}
		
		$cache = new $driver;
		
		if ($driver == 'File') {
			$options = array_merge($this->options, $options);
			
			if (!isset($options['cacheDir'])) {
				throw new \InvalidArgumentException('The parameter "cacheDir" must be defined when using the File driver.');
			}
			$cache->setCacheDir($options['cacheDir']);
		}
		
		if ($driver == 'Memcache') {
			$options = array_merge($this->options, $options);
		
			if (!isset($options['memcache'])) {
				throw new \InvalidArgumentException('The parameter "memcache" must be defined when using the Memcache driver.');
			}
			$cache->setMemcache($options['memcache']);
		}
		
		return $cache;
	}
	
	/**
	 * Check if the given driver is supported by the bundle
	 *
	 * @param string $driver
	 * @return bool
	 */
	public function driverExists($driver)
	{
		return in_array($driver, array('File', 'ZendData', 'Memcache', 'Apc', 'Array', 'Xcache'));
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
			case 'File':
			case 'Array':
				return true;
				break;
			
			case 'ZendData':
				return function_exists('zend_shm_cache_fetch');
				break;
			
			case 'Memcache':
				return class_exists('Memcache');
				break;
			
			case 'Apc':
				return function_exists('apc_fetch');
				break;
			
			case 'Xcache':
				return function_exists('xcache_get');
				break;
		}
		
		return false;
	}
	
}
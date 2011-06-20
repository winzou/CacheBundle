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

namespace winzou\CacheBundle\Cache;

/**
 * File cache driver.
 *
 * @author  winzou
 */
class FileCache extends AbstractCache
{
    /** @var string $cacheDir */
    private $cacheDir = '.';
	
	/** @var string $separator */
    private $separator = '--s--';
    
    /**
     * Set the cache directory to use.
     *
     * @param string $cacheDir
     */
    public function setCacheDir($cacheDir)
    {
        if (!is_dir($cacheDir) && !mkdir($cacheDir, 777)) {
            throw new \Exception('Unable to create the directory "'.$cacheDir.'"');
        }
        
        if (in_array(substr($cacheDir, -1), array('\\', '/'))) {
            $cacheDir = substr($cacheDir, 0, -1):
        }    
        
        $this->cacheDir = $cacheDir;
    }
    
	/**
     * Get the file name from a cache id.
     *
     * @param string $id
     */
    private function getFileName($id)
    {
        return $this->cacheDir.str_replace(DIRECTORY_SEPARATOR, $this->separator, $id).'.php';
    }
	
	/**
     * Get the cache id from a file name.
     *
     * @param string $file
     */
    private function getKeyName($file)
    {
        return str_replace($this->separator, DIRECTORY_SEPARATOR, substr(basename($file),0,-4));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIds()
    {
        $keys = glob($this->cacheDir.DIRECTORY_SEPARATOR.'*');
        $keys = array_map(array($this, 'getKeyName'), $keys);
        
        return $keys;
    }

    /**
     * {@inheritdoc}
     */
    protected function _doFetch($id)
    {
        return include $this->getFileName($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doContains($id)
    {
        return file_exists($this->getFileName($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function _doSave($id, $data, $lifeTime = 0)
    {
        return (bool) file_put_contents($this->getFileName($id), '<?php return '.var_export($data));
    }

    /**
     * {@inheritdoc}
     */
    protected function _doDelete($id)
    {
        return unlink($this->getFileName($id));
    }
}
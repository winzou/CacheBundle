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
 * File cache driver with lifetime support.
 *
 * @author Thibaut Cuvelier
 */
class LifetimeFileCache extends FileCache
{
    /**
     * {@inheritdoc}
     */
    protected function _doFetch($id)
    {
        $data = unserialize(file_get_contents($this->getFileName($id))); 
        return $data['data'];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _doContains($id)
    {
        $name = $this->getFileName($id);
        if(! parent::_doContains($id))
            return false;
        
        $file = unserialize(file_get_contents($name)); 
        
        if ((time() - @filemtime($file)) < $file['lt'])
        {
            $this->_doDelete($id);
            return false;
        }
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _doSave($id, $data, $lifeTime = 0)
    {
        return (bool) file_put_contents($this->getFileName($id), serialize(array('data' => $data, 'lt' => $lifeTime)));
    }
}
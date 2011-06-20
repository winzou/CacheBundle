winzouCacheBundle
============

What's that?
--------------
winzouCacheBundle provides a simple cache management. Now you can use a cache system without reinventing it.
It supports Apc, XCache, File, ZendData and Array.

Usage
-----
config.yml:

        winzou_cache:
            driver:
                default: File
            options:
                cache_dir: winzou_cache

In your controller:

        $cache = $this->get('winzou_cache.cache');
        
        $cache->save('bar', array('foo', 'bar'));
        
        $bar = $cache->fetch('bar');

See Cache\AbstractCache for all the available methods.
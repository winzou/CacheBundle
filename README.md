winzouCacheBundle
============

What's that?
--------------
winzouCacheBundle provides a simple cache management. Now you can use a cache system without reinventing it.
It supports Apc, XCache, File, ZendData and Array.

Usage
-----
In your controller:

    $cache = $this->get('winzou_cache.apc');
    // or
    $cache = $this->get('winzou_cache.file');
    // or
    $cache = $this->get('winzou_cache.memcache');

    $cache->save('bar', array('foo', 'bar'));
    $bar = $cache->fetch('bar');

See Cache\AbstractCache for all the available methods.

Configuration
-------------
When using FileCache, if you don't want to store your cache files in `ROOT_DIR/cache/ENV/winzou_cache`, then define the absolute path in your config.yml:

    winzou_cache:
        options:
            cache_dir: %kernel.root_dir%/cache/%kernel.environment%/your_directory
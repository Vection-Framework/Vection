<?php

# APCu example

# First we have to create a cache provider. As we want to use the apcu, we use the build-in APCuCacheProvider

$provider = new \Vection\Component\Cache\Provider\APCuCacheProvider();

# On principle you can work with this provider as a standalone cache provider
# but you have to resign on features like cache pools or namespaces for cache keys.
# So let create the proper cache instance we want to use.

$cache = new \Vection\Component\Cache\Cache($provider, 'MyCustomNamespace', '/');

# On creation you can optionally determine the root namespace by the second parameter, which you would like use
# for the cache keys. The last parameter we can optionally set a separator for nested cache pools
# (separator between namespaces). If you don't set this parameter, the cache sets a ":" as default separator.

# Lets work with the cache object by using some flat operations.

if( ! $cache->contains('foo') ){
    $cache->set('foo', 'bar');
}

$plain = $cache->get('foo');


# You can work also with type safety values by using the "type-setter/getter"

$cache->setArray('values', ['a','b']);

$array = $cache->getArray('value');

# So you can be sure you will get an array or null (or default value, by param 2)
<?php

namespace Auth\Service\Storage;

use Auth\Contract\Storage;
use Zend\Cache\Psr\SimpleCache\SimpleCacheDecorator;

class CacheStorage implements Storage
{
    /**
     * @var SimpleCacheDecorator
     */
    private $cache;

    /**
     * CacheStorage constructor.
     *
     * @param SimpleCacheDecorator $cache
     */
    public function __construct(SimpleCacheDecorator $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param $key
     * @param $value
     * @param $ttl
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function add($key, $value, $ttl)
    {
        return $this->cache->set($key, $value, $ttl);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function has($key)
    {
        return $this->cache->has($key);
    }
}
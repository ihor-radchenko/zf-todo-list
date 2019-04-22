<?php

namespace Auth\Contract;

interface Storage
{
    /**
     * @param $key
     * @param $value
     * @param $ttl
     *
     * @return mixed
     */
    public function add($key, $value, $ttl);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function has($key);
}
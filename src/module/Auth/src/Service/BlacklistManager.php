<?php

namespace Auth\Service;

use Auth\Contract\Storage;

class BlacklistManager
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * BlacklistManager constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $jti
     *
     * @return bool
     */
    public function isValid(string $jti): bool
    {
        return !$this->storage->has('jwt_' . $jti);
    }

    /**
     * @param string $jti
     * @param $ttl
     *
     * @return mixed
     */
    public function invalidate(string $jti, $ttl)
    {
        return $this->storage->add('jwt_' . $jti, 'value', null);
    }
}
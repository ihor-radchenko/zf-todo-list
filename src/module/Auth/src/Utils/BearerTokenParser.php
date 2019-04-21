<?php

namespace Auth\Utils;

class BearerTokenParser
{
    /**
     * @param string $header
     *
     * @return string|null
     */
    public static function parse(string $header): ?string
    {
        if (preg_match('/Bearer (\S*)/', $header, $match)) {
            return $match[1];
        }

        return null;
    }
}
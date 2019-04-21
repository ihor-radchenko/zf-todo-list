<?php

namespace Auth\Contract;

interface JwtSubjectInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;
}

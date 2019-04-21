<?php

namespace Auth\Service;

use Auth\Repository\UserRepository;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class JwtAuthAdapter implements AdapterInterface
{
    /**
     * @var string|null
     */
    private $token;

    /**
     * @var JwtManager
     */
    private $jwtManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * JwtAuthAdapter constructor.
     *
     * @param JwtManager $jwtManager
     * @param UserRepository $userRepository
     */
    public function __construct(JwtManager $jwtManager, UserRepository $userRepository)
    {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     * @throws \Auth\Exception\TokenInvalidException
     */
    public function authenticate()
    {
        if ($this->getToken() !== null) {
            $tokenCredentials = $this->jwtManager->decode($this->getToken());
            if ($tokenCredentials['acc'] && $this->jwtManager->validate($tokenCredentials)) {
                $user = $this->userRepository->find($tokenCredentials['sub']);
                if ($user === null) {
                    return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
                }

                return new Result(Result::SUCCESS, $user);
            }
        }

        return new Result(Result::FAILURE, null);
    }

    /**
     * @param string $token
     *
     * @return JwtAuthAdapter
     */
    public function setToken(string $token): JwtAuthAdapter
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}

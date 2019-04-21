<?php

namespace Auth\Service;

use Auth\Contract\JwtSubjectInterface;
use Auth\Exception\JwtException;
use Auth\Exception\TokenInvalidException;
use Carbon\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Rsa\Sha256 as RS256;
use Lcobucci\JWT\Signer\Rsa\Sha384 as RS384;
use Lcobucci\JWT\Signer\Rsa\Sha512 as RS512;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HS256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HS384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HS512;
use Lcobucci\JWT\Signer\Ecdsa\Sha256 as ES256;
use Lcobucci\JWT\Signer\Ecdsa\Sha384 as ES384;
use Lcobucci\JWT\Signer\Ecdsa\Sha512 as ES512;
use Zend\View\Helper\ServerUrl;

class JwtManager
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var Signer
     */
    private $signer;

    /**
     * @var array
     */
    private $signers = [
        'HS256' => HS256::class,
        'HS384' => HS384::class,
        'HS512' => HS512::class,
        'RS256' => RS256::class,
        'RS384' => RS384::class,
        'RS512' => RS512::class,
        'ES256' => ES256::class,
        'ES384' => ES384::class,
        'ES512' => ES512::class,
    ];
    /**
     * @var ServerUrl
     */
    private $serverUrl;

    /**
     * @var int
     */
    private $jtiLength;

    /**
     * @var int
     */
    private $accessTtl;

    /**
     * @var int
     */
    private $refreshTtl;

    /**
     * JwtManager constructor.
     *
     * @param Builder $builder
     * @param Parser $parser
     * @param ServerUrl $serverUrl
     * @param array $jwtConfig
     *
     * @throws JwtException
     */
    public function __construct(Builder $builder, Parser $parser, ServerUrl $serverUrl, array $jwtConfig)
    {
        $this->builder = $builder;
        $this->parser = $parser;
        $this->serverUrl = $serverUrl;
        $this->secret = $jwtConfig['secret'];
        $this->algorithm = strtoupper($jwtConfig['algorithm']);
        $this->jtiLength = $jwtConfig['jti_length'];
        $this->accessTtl = $jwtConfig['ttl_access'];
        $this->refreshTtl = $jwtConfig['ttl_refresh'];
        $this->signer = $this->getSigner();
    }

    /**
     * @param JwtSubjectInterface $subject
     *
     * @return array
     * @throws \Exception
     */
    public function getFromSubject(JwtSubjectInterface $subject): array
    {
        return [
            'type'          => 'bearer',
            'access_token'  => $this->encode($subject, true),
            'refresh_token' => $this->encode($subject, false),
            'expired_in'    => $this->accessTtl * Carbon::SECONDS_PER_MINUTE,
        ];
    }

    /**
     * @param JwtSubjectInterface $subject
     * @param bool $isAccess
     *
     * @return string
     * @throws \Exception
     */
    public function encode(JwtSubjectInterface $subject, bool $isAccess): string
    {
        $this->builder->unsign();

        $now = Carbon::now();
        $expiredAt = $isAccess
            ? Carbon::now()->addMinutes($this->accessTtl)
            : Carbon::now()->addMinutes($this->refreshTtl);

        $this->builder->setIssuer($this->serverUrl->getHost());
        $this->builder->setIssuedAt($now->getTimestamp());
        $this->builder->setNotBefore($now->getTimestamp());
        $this->builder->setExpiration($expiredAt->getTimestamp());
        $this->builder->setId($this->generateJti());
        $this->builder->setSubject($subject->getId());
        $this->builder->set('acc', $isAccess);

        $this->builder->sign($this->signer, $this->secret);

        return (string) $this->builder->getToken();
    }

    /**
     * @param string $token
     *
     * @return array
     * @throws TokenInvalidException
     */
    public function decode(string $token): array
    {
        try {
            $jwt = $this->parser->parse($token);
        } catch (\Exception $exception) {
            throw new TokenInvalidException('Could not decode token.');
        }

        if (! $jwt->verify($this->signer, $this->secret)) {
            throw new TokenInvalidException('Token Signature could not be verified.');
        }

        return array_map(static function ($claim) {
            return is_object($claim) ? $claim->getValue() : $claim;
        }, $jwt->getClaims());
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data): bool
    {
        return $data['iss'] === $this->serverUrl->getHost() && Carbon::createFromTimestamp($data['exp'])->greaterThan(Carbon::now());
    }

    /**
     * @return Signer
     * @throws JwtException
     */
    private function getSigner(): Signer
    {
        if (!array_key_exists($this->algorithm, $this->signers)) {
            throw new JwtException('Wrong algorithm.');
        }

        return new $this->signers[$this->algorithm];
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateJti(): string
    {
        $string = '';

        while (($len = strlen($string)) < $this->jtiLength) {
            $size = $this->jtiLength - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

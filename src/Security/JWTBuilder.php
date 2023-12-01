<?php
declare(strict_types=1);

namespace App\Security\Jwt;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Class JwtBuilder
 */
class JWTBuilder
{
    private Configuration $configuration;

    public function __construct(string $secretKey)
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($secretKey)
        );
    }

    public function generateToken($user): string
    {
        $now = new DateTimeImmutable();
        $token = $this->configuration->builder()
            // Configures the issuer (iss claim)
            ->issuedBy($_SERVER['HTTP_HOST'])
            // Configures the audience (aud claim)
            ->permittedFor($_SERVER['HTTP_HOST'])
            // Configures the id (jti claim)
            ->identifiedBy($user)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('username', $user)
            // Builds a new token
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }
}
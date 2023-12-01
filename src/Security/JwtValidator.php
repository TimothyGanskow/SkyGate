<?php

declare(strict_types=1);

namespace App\Security\Jwt;

use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class JwtValidator
 */
class JwtValidator
{
    private Configuration $configuration;

    public function __construct(string $secretKey)
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($secretKey)
        );
        $this->configuration->setValidationConstraints(
            new SignedWith(new Sha256(), InMemory::base64Encoded($secretKey))
        );
    }

    public function validate(string $jwt): string
    {
        // first: parse the JWT
        try {
            $token = $this->configuration->parser()->parse($jwt);
        } catch (Exception) {
            throw new AuthenticationException('INVALID_JWT');
        }

        if (!$token instanceof UnencryptedToken) {
            throw new AuthenticationException('INVALID_JWT');
        }

        // second: validate the parsed JWT
        $constraints = $this->configuration->validationConstraints();

        if (!$this->configuration->validator()->validate($token, ...$constraints)) {
            throw new AuthenticationException('INVALID_JWT');
        }

        return $token->claims()->get('username');
    }
}

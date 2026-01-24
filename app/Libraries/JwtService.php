<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

class JwtService
{
    protected JWTConfig $config;

    public function __construct()
    {
        $this->config = config('JWT');
    }

    public function generate(array $payload): string
    {
        $time = time();

        $tokenPayload = array_merge($payload, [
            'iat' => $time,
            'exp' => $time + $this->config->ttl,
        ]);

        return JWT::encode($tokenPayload, $this->config->key, $this->config->alg);
    }

    public function validate(string $token)
    {
        return JWT::decode($token, new Key($this->config->key, $this->config->alg));
    }
}

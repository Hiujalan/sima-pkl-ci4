<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $key;
    public string $alg;
    public int $ttl;

    public function __construct()
    {
        parent::__construct();

        $this->key = env('JWT_SECRET', 'oTP3c5NfPBbcuym1M08VJrd4Bd88DDwy');
        $this->alg = env('JWT_ALGO', 'HS256');
        $this->ttl = (int) env('JWT_TTL', 3600);
    }
}

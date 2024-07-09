<?php

namespace Reactmore\ChainshubApiWrapper;

use Dotenv\Dotenv;

class Config
{
    public static function load()
    {
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }

        return [
            'api_key' => $_ENV['API_KEY'],
            'network' => $_ENV['DEVELOPMENT'] ? 'testnet' : 'mainet',
            'merchant_private_key' => $_ENV['MERCHANT_PRIVATE_KEY'],
            'base_url' => $_ENV['BASE_URL'] ?? 'https://adelaide.chainshub.id/api',
        ];
    }
}

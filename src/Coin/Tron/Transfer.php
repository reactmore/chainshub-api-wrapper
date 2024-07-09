<?php

namespace Reactmore\ChainshubApiWrapper\Coin\Tron;

use Reactmore\ChainshubApiWrapper\Coin\CoinInterface;
use Reactmore\ChainshubApiWrapper\Request\GuzzleClient;

class Transfer implements CoinInterface
{
    private $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function handle($params)
    {
        return $this->client->post('/tron/transfer', $params);
    }
}

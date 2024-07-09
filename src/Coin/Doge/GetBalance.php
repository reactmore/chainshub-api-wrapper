<?php

namespace Reactmore\ChainshubApiWrapper\Coin\Doge;

use Reactmore\ChainshubApiWrapper\Coin\CoinInterface;
use Reactmore\ChainshubApiWrapper\Request\GuzzleClient;

class GetBalance implements CoinInterface
{
    private $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function handle($params)
    {
        return $this->client->post('/doge/balance', $params);
    }
}

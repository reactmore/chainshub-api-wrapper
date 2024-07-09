<?php

namespace Reactmore\ChainshubApiWrapper\MerchantTransaction;

use Reactmore\ChainshubApiWrapper\Request\GuzzleClient;

class CreateInvoice
{
    private $client;
    private $merchantPrivateKey;

    public function __construct(GuzzleClient $client, $merchantPrivateKey)
    {
        $this->client = $client;
        $this->merchantPrivateKey = $merchantPrivateKey;
    }

    public function handle($params)
    {
        $signature = hash_hmac('sha256', $params['merchant_ref'].$params['currency'].$params['pair'].$params['amount'], $this->merchantPrivateKey);
        $params['signature'] = $signature;

        return $this->client->post('/transaction/create', $params);
    }
}

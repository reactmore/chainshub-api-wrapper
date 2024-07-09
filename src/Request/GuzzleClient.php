<?php

namespace Reactmore\ChainshubApiWrapper\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleClient
{
    private $client;
    private $apiKey;

    public function __construct($apiKey, $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function post($uri, $params)
    {
        try {
            $response = $this->client->post($uri, [
                'json' => $params
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}

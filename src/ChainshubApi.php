<?php

namespace Reactmore\ChainshubApiWrapper;

use Reactmore\ChainshubApiWrapper\Request\GuzzleClient;
use Reactmore\ChainshubApiWrapper\Coin\CoinInterface;
use Reactmore\ChainshubApiWrapper\MerchantTransaction\CreateInvoice;

class ChainshubApi
{
    private $client;
    private $apiKey;
    private $merchantPrivateKey;

    public function __construct($apiKey = null, $merchantPrivateKey = null)
    {
        $config = Config::load();

        $this->apiKey = $apiKey ?? $config['api_key'];
        if (empty($this->apiKey)) {
            echo json_encode([
                'status' => false,
                'message' => 'API Key is required'
            ]);
            exit;
        }
        $this->merchantPrivateKey = $merchantPrivateKey ?? $config['merchant_private_key'];
        $baseUrl = $config['base_url'];

        $this->client = new GuzzleClient($this->apiKey, $baseUrl);
    }

    public function __call($name, $arguments)
    {
        $parts = explode('_', $name);
        if (count($parts) !== 2) {
            throw new \BadMethodCallException("Method {$name} does not exist.");
        }

        [$coin, $action] = $parts;
        $className = "Reactmore\\ChainshubApiWrapper\\Coin\\" . ucfirst($coin) . "\\" . ucfirst($action);

        if (!class_exists($className)) {
            throw new \BadMethodCallException("Class {$className} does not exist.");
        }

        $instance = new $className($this->client);
        if (!$instance instanceof CoinInterface) {
            throw new \BadMethodCallException("Class {$className} does not implement CoinInterface.");
        }

        return $instance->handle(...$arguments);
    }

    public function createInvoice($params)
    {
        $instance = new CreateInvoice($this->client, $this->merchantPrivateKey);
        return $instance->handle($params);
    }

    public function handleCallback($postData, $headers)
    {
        $incomingSignature = isset($headers['CH-CALLBACK-SIGNATURE']) ? $headers['CH-CALLBACK-SIGNATURE'] : null;

        $signature = hash_hmac('sha256', $postData, $this->merchantPrivateKey);

        if (!hash_equals($incomingSignature, $signature)) {
            return [
                'status' => false,
                'message' => 'Signature Invalid'
            ];
        }

        $data = json_decode($postData, true);
        $successMark = ["Completed"];

        if (in_array($data['transaction_status'], $successMark)) {
            return [
                'status' => true,
                'message' => 'Payment Completed',
                'merchant_ref' => $data['merchant_ref']
            ];
        } elseif ($data['transaction_status'] == 'Transaction Expired') {
            return [
                'status' => true,
                'message' => 'Transaction Expired',
                'merchant_ref' => $data['merchant_ref']
            ];
        }

        return [
            'status' => false,
            'message' => 'Unknown Status',
            'merchant_ref' => $data['merchant_ref']
        ];
    }
}

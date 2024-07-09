<?php

use PHPUnit\Framework\TestCase;
use Reactmore\ChainshubApiWrapper\ChainshubApi;
use Dotenv\Dotenv;

class ChainshubApiTest extends TestCase
{
    private $api;

    protected function setUp(): void
    {
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }

        $config = \Reactmore\ChainshubApiWrapper\Config::load();

        $apiKey = $config['api_key'];
        $merchantPrivateKey = $config['merchant_private_key'];

        $this->api = new ChainshubApi($apiKey, $merchantPrivateKey);
    }

    public function testGenerateAddress()
    {
        $params = ['network' => 'mainnet'];
        $response = $this->api->tron_generateAddress([$params]);
        $this->assertArrayHasKey('status', $response);
        $this->assertTrue($response['status']);
    }

    public function testGetBalance()
    {
        $params = [
            'network' => 'mainnet',
            'address' => 'your-tron-address-here'
        ];
        $response = $this->api->tron_getBalance([$params]);
        $this->assertArrayHasKey('status', $response);
        $this->assertTrue($response['status']);
    }

    public function testTransfer()
    {
        $params = [
            'network' => 'mainnet',
            'from_address' => 'your-tron-address-here',
            'amount' => 5,
            'to_address' => 'recipient-tron-address-here',
            'private_key' => 'your-private-key-here'
        ];
        $response = $this->api->tron_transfer([$params]);
        $this->assertArrayHasKey('status', $response);
        $this->assertTrue($response['status']);
    }

    public function testCreateInvoice()
    {
        $params = [
            'currency' => 'bsc',
            'merchant_ref' => 'INVOICE-001',
            'customer_email' => 'customer@gmail.com',
            'customer_name' => 'James Bond',
            'pair' => 'idr',
            'redirect_url' => 'https://yourwebsite.com/callback=success',
            'cancel_url' => 'https://yourwebsite.com/callback=failed',
            'amount' => '10000'
        ];
        $response = $this->api->createInvoice($params);
        $this->assertArrayHasKey('status', $response);
        $this->assertTrue($response['status']);
    }

    public function testHandleCallback()
    {
        $postData = '{"transaction_status":"Completed","merchant_ref":"INVOICE-001"}';
        $headers = [
            'CH-CALLBACK-SIGNATURE' => hash_hmac('sha256', $postData, $_ENV['MERCHANT_PRIVATE_KEY'] ?? 'your-merchant-private-key')
        ];
        $response = $this->api->handleCallback($postData, $headers);
        $this->assertTrue($response['status']);
        $this->assertEquals('Payment Completed', $response['message']);
    }
}

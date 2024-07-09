# chainshub-api-wrapper

# Installation
```bash
composer require reactmore/chainshub-api-wrapper
```

# Coin Api Wrapper
| Coin  | Status | 
|---|---|
| `Tron` | OK |
| `Doge` | Not Yet |
| `BSC` | Not Yet |
| `ETC` | Not Yet |
| `LTC` | Not Yet |
| `DASH` | Not Yet |



# Calling Coin Method
$api->{CoinName}_{{MethodName}}();
ex: 
```php
$api->tron_generateAddress();
$api->tron_getBalance();
$api->tron_transfer();
etc....
```

# Example Usage Coin Method
Load package
```php
<?php

require 'vendor/autoload.php';

use Reactmore\ChainshubApiWrapper\ChainshubApi;

// Load .env if exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Define Your API Key and Merchant Private Key
$apiKey = $_ENV['API_KEY'] ?? 'your-api-key-here';
$merchantPrivateKey = $_ENV['MERCHANT_PRIVATE_KEY'] ?? 'your-merchant-private-key';

// Initialize the ChainshubApi class
$api = new ChainshubApi($apiKey, $merchantPrivateKey);

```

Generate Address
```php
$api = new ChainshubApi($apiKey, $merchantPrivateKey);

// Generate a new address
try {
    $generateAddressParams = ['network' => 'mainnet'];
    $generateAddressResponse = $api->tron_generateAddress($generateAddressParams);
    echo "Generate Address Response:\n";
    print_r($generateAddressResponse);
} catch (Exception $e) {
    echo "Error generating address: " . $e->getMessage() . "\n";
}
```

Check Balance
```php
$api = new ChainshubApi($apiKey, $merchantPrivateKey);

// Check balance
try {
    $balanceParams = [
        'network' => 'mainnet',
        'address' => 'your-tron-address-here'
    ];
    $balanceResponse = $api->tron_getBalance($balanceParams);
    echo "Balance Response:\n";
    print_r($balanceResponse);
} catch (Exception $e) {
    echo "Error checking balance: " . $e->getMessage() . "\n";
}
```
Transfer Coin
```php
$api = new ChainshubApi($apiKey, $merchantPrivateKey);

// Transfer coins
try {
    $transferParams = [
        'network' => 'mainnet',
        'from_address' => 'your-tron-address-here',
        'amount' => 5,
        'to_address' => 'destination-tron-address-here',
        'private_key' => 'your-private-key-here'
    ];
    $transferResponse = $api->tron_transfer($transferParams);
    echo "Transfer Response:\n";
    print_r($transferResponse);
} catch (Exception $e) {
    echo "Error transferring coins: " . $e->getMessage() . "\n";
}
```

# Example Merchant Transactions
Create Invoice:
```php
// Create an invoice
try {
    $invoiceParams = [
        'currency' => 'bsc',
        'merchant_ref' => 'INVOICE-001',
        'customer_email' => 'customer@gmail.com',
        'customer_name' => 'James Bond',
        'pair' => 'idr',
        'redirect_url' => 'https://yourwebsite.com/callback=success',
        'cancel_url' => 'https://yourwebsite.com/callback=failed',
        'amount' => '10000'
    ];
    $invoiceResponse = $api->createInvoice($invoiceParams);
    echo "Create Invoice Response:\n";
    print_r($invoiceResponse);
} catch (Exception $e) {
    echo "Error creating invoice: " . $e->getMessage() . "\n";
}
```
Handle Callback: 
```php

// Handle callback
try {
    $postData = file_get_contents('php://input');
    $headers = getallheaders();
    $callbackResponse = $api->handleCallback($postData, $headers);
    echo "Callback Response:\n";
    print_r($callbackResponse);
} catch (Exception $e) {
    echo "Error handling callback: " . $e->getMessage() . "\n";
}
```
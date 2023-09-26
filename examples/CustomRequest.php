<?php
use Getnet\API\Token;

require_once '../config/bootstrap.test.php';

//Autenticação da API
$getnet = getnetServiceTest();

// List customers
$response = $getnet->customRequest('GET', '/v1/customers?page=1&limit=5');
print_r($response['customers']);

$customer_id = 'customer_210818263';

// Generate card Token
$tokenCard = new Token("5155901222280001", $customer_id, $getnet);

// Save card Token
$cardResponse = $getnet->customRequest('POST', '/v1/cards', [
    "number_token" => $tokenCard->getNumberToken(),
    "brand" => "Mastercard",
    "cardholder_name" => "JOAO DA SILVA",
    "expiration_month" => "12",
    "expiration_year" => "30",
    "customer_id" => $customer_id,
    "cardholder_identification" => "12345678912",
    "verify_card" => false,
    "security_code" => "123"
]);
print_r($cardResponse['card_id'], $cardResponse['number_token']);

// Get card token by card_id
$response = $getnet->customRequest('GET', "/v1/cards/{$cardResponse['card_id']}");
print_r($response);

// List card token by customer
$response = $getnet->customRequest('GET', "/v1/cards?customer_id=$customer_id");
print_r($response['cards']);

// Delete card token by card_id
$response = $getnet->customRequest('DELETE', "/v1/cards/{$cardResponse['card_id']}");
print_r($response['status_code'] === 204);
<?php

include_once 'categories.php';
include_once 'products.php';
include_once 'customers.php';
include_once 'categorie_products.php';
include_once 'stock.php';
include_once 'orders.php';

$apiUser = "soapuser";
$apiKey = "azerty123";

if (empty($apiUser) && empty($apiKey))
{
    echo "Champs vides";
}
else
{
    $client = new SoapClient('http://127.0.0.1/magento/api/v2_soap/?wsdl');
    $sessionId = $client->login($apiUser, $apiKey);
    if (isset($_POST['products']))
    {
        get_products($client, $sessionId);
        get_categories($client, $sessionId);
        get_customers($client, $sessionId);
        categories_products_link($client, $sessionId);
        get_stocks($client, $sessionId);
        //get_orders($client, $sessionId);
    }
}
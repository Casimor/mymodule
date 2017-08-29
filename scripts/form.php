<?php

include_once 'categories.php';
include_once 'products.php';
include_once 'customers.php';
include_once 'categorie_products.php';
include_once 'stock.php';
include_once 'orders.php';

setlocale(LC_CTYPE, 'fr_FR');

$apiUser = "soapuser";
$apiKey = "s0d3z1gn";

if (empty($apiUser) && empty($apiKey))
{
    echo "Champs vides";
}
else
{


    $wsdlUrl = 'https://erp.sodezign.com/index.php/api/v2_soap/index/wsdl/1/';
    $client = new SoapClient($wsdlUrl);
    $sessionId = $client->login($apiUser, $apiKey);
    if (isset($_POST['products']))
    {
        //get_products($client, $sessionId);
        get_categories($client, $sessionId);
        //get_customers($client, $sessionId);
        //categories_products_link($client, $sessionId);
        //get_stocks($client, $sessionId);
        //get_orders($client, $sessionId);
    }
}
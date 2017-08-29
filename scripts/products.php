<?php

include_once 'functions.php';

/*
** Import products from Magento to Dolibarr
** Fill llx_product
*/

function    lipstick($str)
{
    $str = str_replace("é", "e", $str);
    $str = str_replace("è", "e", $str);
    $str = str_replace("'", " ", $str);
    return $str;
}

function    insert_product($data, $conn)
{
    $ref = $data['product_id'];
    $ref_ext = lipstick($data['sku']);
    $label = lipstick($data['name']);

    if(!isset($data['price']))
        $price = 0;
    else
        $price = (double) $data['price'];

    if(!isset($data['weight']))
        $weight = 0;
    else
        $weight = (float) $data['weight'];

    $query = "INSERT INTO llx_product (ref, ref_ext, label, price, weight, tosell, tobuy) VALUES ('$ref', '$ref_ext', '$label', '$price', '$weight', 1, 1)";    
    querysql($conn, $query);
}

function 	get_products($client, $sessionId)
{
	$conn = connection_db("dolibarr");
	$products = $client->catalogProductList($sessionId);
	querysql($conn, "ALTER TABLE llx_product AUTO_INCREMENT = 1");
	foreach ($products as $productObj)
	{
        $product = (array) $productObj;
    	$info = $client->catalogProductInfo($sessionId, $product['product_id']);
        $info = (array) $info;
        insert_product($info, $conn);
    }
	$conn = null;
}

<?php

include_once 'functions.php';

function 	sort_info($data, $conn)
{
	$array = explode("'", $data['name']);
	if ($array)
		$ref = implode(" ", $array);
	else
		$ref = $data['name'];
	$ref_ext = $data['sku'];
	$entity = intval($data['product_id']);
	$label = $data['type_id'];
	$price = (double)$data['price'];
	$url = $data['url_path'];
	$weight = (float)$data['weight'];

	$query = "INSERT INTO llx_product (ref, ref_ext, entity, label, price, url, weight, fk_user_author, fk_user_modif) VALUES ('$ref', '$ref_ext', '$entity', '$label', $price, '$url', $weight, 1, 1)";
	
	querysql($conn, $query);
}

function 	get_products($client, $sessionId)
{
	$conn = connection_db("dolibarr");
	$products = $client->catalogProductList($sessionId);
	querysql($conn, "ALTER TABLE llx_product AUTO_INCREMENT = 1");
	foreach ($products as $productObj)
	{
    	$product = get_object_vars($productObj);
    	$infos = $client->catalogProductInfo($sessionId, $product['product_id']);
    	$info = get_object_vars($infos);
    	sort_info($info, $conn);
    	break ;
	}
	$conn = null;
}

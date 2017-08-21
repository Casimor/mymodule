<?php

include_once 'functions.php';

function 	insert_product($data, $conn)
{
    $ref = $data['product_id'];
    $ref_ext = $data['sku'];
    $label = $data['name'];
    if (preg_match("#'#", $label))
        $label = addslashes($label);
    $url = $data['url_path'];

    if(!isset($data['price']))
        $price = 0;
    else
        $price = (double)$data['price'];

    if(!isset($data['weight']))
        $weight = 0;
    else
        $weight = (float)$data['weight'];

    $query = "INSERT INTO llx_product (ref, ref_ext, label, price, url, weight, tosell, tobuy) VALUES ('$ref', '$ref_ext', :label, '$price', '$url', '$weight', 1, 1)";
    
    $req = $conn->prepare($query);
    $req->execute(array('label' => $label));
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
    	insert_product($info, $conn);
	}
	$conn = null;
}
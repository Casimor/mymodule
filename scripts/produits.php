<?php

include_once 'functions.php';

function 	sort_info($data, $conn)
{

    $ref = $data['product_id'];
    $ref_ext = $data['sku'];
    $label = $data['name'];
    if(preg_match("#'#", $label))
        $label = addslashes($label);
    $url = $data['url_path'];
    $priceIsNull = FALSE;
    $weightIsNull = FALSE;

    if(!isset($data['price']))
        $priceIsNull = TRUE;
    else
        $price = (double)$data['price'];

    if(!isset($data['weight']))
        $weightIsNull = TRUE;
    else
        $weight = (float)$data['weight'];

    if($priceIsNull && $weightIsNull)
        $query = "INSERT INTO llx_product (ref, ref_ext, label, url, tosell, tobuy) VALUES ('$ref', '$ref_ext', :label, '$url', 1, 1)";
    else if($priceIsNull && !$weightIsNull)
        $query = "INSERT INTO llx_product (ref, ref_ext, label, url, weight, tosell, tobuy) VALUES ('$ref', '$ref_ext', :label, '$url', '$weight', 1, 1)";
    else if(!$priceIsNull && $weightIsNull)
        $query = "INSERT INTO llx_product (ref, ref_ext, label, price, url, tosell, tobuy) VALUES ('$ref', '$ref_ext', :label, '$price', '$url', 1, 1)";
    else
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
    	//sort_info($info, $conn);
    	var_dump($infos);
    	break ;
	}
	$conn = null;
}

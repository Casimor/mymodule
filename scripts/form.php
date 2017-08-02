<?php

$apiUser = $_POST['userApi'];
$apiKey = $_POST['keyApi'];

function 	sort_info($data, $conn, $tmp)
{
	$rowid = intval($data['product_id']);
	$array = explode("'", $data['name']);
	if ($array)
		$name = implode(" ", $array);
	else
		$name = $data['name'];
	$entity = $tmp;
	//$datec = date_create_From_Format('Y-m-d H:i:s', $data['created_at']);
	//$tms = $data['updated_at'];
//	$description = $data['description'];
	$label = $data['type_id'];
	$price = (double)$data['price'];
	$url = $data['url_path'];
	$weight = (float)$data['weight'];

	$query = "INSERT INTO llx_product (rowid, ref, entity, label, price, url, weight) VALUES ($rowid, '$name', '$entity', '$label', $price, '$url', $weight)";
	
	//echo $query;
	if (!$conn->query($query))
	    echo "query error";
	else
		echo "\nquery success";
}

function 	connection_db()
{
	$servername = "localhost";
	$username = "root";
	$password = "admin";

	try
	{
	    $conn = new PDO("mysql:host=$servername;dbname=dolibarr", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    return $conn;
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
}

if (!empty($apiUser) && !empty($apiKey))
{
	$tmp = 0;
	$client = new SoapClient('http://127.0.0.1/magento/api/v2_soap/?wsdl'); // TODO : change url
	$sessionId = $client->login($apiUser, $apiKey); // TODO : change login and pwd if necessary
	$conn = connection_db();
	$products = $client->catalogProductList($sessionId);
	foreach ($products as $productObj)
	{
    	$product = get_object_vars($productObj);
    	$infos = $client->catalogProductInfo($sessionId, $product['product_id']);
    	$info = get_object_vars($infos);
    	//var_dump($infos);
    	sort_info($info, $conn, $tmp);
    	$tmp++;
	}
}
else
	echo "Try again !";
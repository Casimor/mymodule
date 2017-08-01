<?php

$apiUser = $_POST['userApi'];
$apiKey = $_POST['keyApi'];

function 	add_product($data)
{
	$result = mysql_query('INSERT INTO `llx_product`(`rowid`, `ref`, `entity`, `datec`, `tms`, `description`, `price`, `url`, `weight`) VALUES ($data["product_id"],$data["name"],$data["sku"], $data["created_at"],$data["updated_at"],$data["description"],$data["price"],$data["url_key"],$data["weight"])');
	if (!$result)
	{
 	   die('Requête invalide : ' . mysql_error());
	}
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
	    echo "Connected successfully";
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
}

if (!empty($apiUser) && !empty($apiKey))
{
	$client = new SoapClient('http://127.0.0.1/magento/api/v2_soap/?wsdl'); // TODO : change url
	$sessionId = $client->login($apiUser, $apiKey); // TODO : change login and pwd if necessary
	connection_db();
	$products = $client->catalogProductList($sessionId);
	$i = 0;
	foreach ($products as $productObj)
	{
    	$product = get_object_vars($productObj);
    	$infos = $client->catalogProductInfo($sessionId, $product["product_id"]);
    	//add_product($infos);
    	var_dump($infos);
    	break ;
    	//print_r($product);
    	//echo $i;
    	//$i++;
	}
	//var_dump($products);
}
else
	echo "Try again !";
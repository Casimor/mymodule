<?php

include_once 'functions.php';

function    create_stock($data, $conn)
{
    $fk_product = get_rowid($data['product_id'], "llx_product", "ref", $conn);
    $fk_entrepot = 1; //temp
    $reel = (real) $data['qty'];
    $query = "INSERT INTO llx_product_stock (fk_product, fk_entrepot, reel) VALUES ('$fk_product', '$fk_entrepot', '$reel')";
    querysql($conn, $query);
}


function    get_stocks($client, $sessionId)
{
    $conn = connection_db("dolibarr");
    $products = $client->catalogProductList($sessionId);
    querysql($conn, "ALTER TABLE llx_product_stock AUTO_INCREMENT = 1");
    foreach ($products as $productObj)
    {
        $product = get_object_vars($productObj);
        $infos = $client->catalogInventoryStockItemList($sessionId, array($product['product_id'], $product['sku']));
        $info = get_object_vars($infos[0]);
        create_stock($info, $conn);
    }
    $conn = null;

}
<?php

include_once 'functions.php';

/*
** Make the connection between categorie and product
** Fill llx_categorie_product
*/

function    insert_cat_prod($conn, $info)
{
    $id_prod = $info['product_id'];
    $rowid_prod = get_rowid($id_prod, "llx_product", "ref", $conn);
    foreach ($info['category_ids'] as $key => $id_cat)
    {
        $rowid_cat = get_rowid($id_cat, "llx_categorie", "id_ext", $conn);
        $query = "INSERT INTO llx_categorie_product (fk_categorie, fk_product) VALUES ('$rowid_cat', '$rowid_prod')";
        querysql($conn, $query);   
    }
}

function    categories_products_link($client, $sessionId)
{
    $conn = connection_db("dolibarr");
    $products = $client->catalogProductList($sessionId);
    foreach ($products as $productObj)
    {
        $product = (array) $productObj;
        $info = (array) $client->catalogProductInfo($sessionId, $product['product_id']);
        insert_cat_prod($conn, $info);
    }
    $conn = null;
}

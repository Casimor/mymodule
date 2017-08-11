<?php

include_once 'functions.php';


function    get_rowid_prod($id, $conn)
{
    $ret = $conn->prepare("SELECT rowid FROM llx_product WHERE ref='$id'");
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    return $result[0];
}

function    get_rowid_cat($id, $conn)
{
    $ret = $conn->prepare("SELECT rowid FROM llx_categorie WHERE id_ext='$id'");
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    return $result[0];
}

function    get_cat_prod_ids($conn, $info)
{
    $id_prod = $info['product_id'];
    $rowid_prod = get_rowid_prod($id_prod, $conn);
    foreach ($info['category_ids'] as $key => $id_cat)
    {
        $rowid_cat = get_rowid_cat($id_cat, $conn);
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
        $product = get_object_vars($productObj);
        $infos = $client->catalogProductInfo($sessionId, $product['product_id']);
        $info = get_object_vars($infos);
        get_cat_prod_ids($conn, $info);
    }
    $conn = null;
}

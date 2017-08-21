<?php

include_once "functions.php";
include_once "create_client.php";

//WIP

function    get_rowid_client($id, $conn)
{
    $ret = $conn->prepare("SELECT rowid FROM llx_societe WHERE nom='$id'");
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    return $result[0];
}

function    check_client($id, $conn)
{
    $query = "SELECT rowid FROM llx_societe WHERE nom='$id'";
    if ($conn->query($query))
        return get_rowid_client($id, $conn);
    else
        return FALSE;
}

function    get_client($data, $client, $sessionId, $conn)
{
    $tmp = array($data['customer_firstname'], $data['customer_lastname']);
    $name = implode(" ", $tmp);
    $exist = check_client($name, $conn);
    if (!$exist)
        create_client();
    else
        echo "ok";


}

function    get_orders($client, $sessionId)
{
    $conn = connection_db("dolibarr");
    $orders = $client->salesOrderList($sessionId, null);
    //querysql($conn, "ALTER TABLE llx_product_stock AUTO_INCREMENT = 1");
    foreach ($orders as $orderObj)
    {
        $order = get_object_vars($orderObj);
        $infos = $client->salesOrderInfo($sessionId, $order['increment_id']);
        $info = get_object_vars($infos);
        //var_dump($info);
        get_client($info, $client, $sessionId, $conn);
        break ;
    }
    //$conn = null;

}
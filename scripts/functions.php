<?php

/*
** connection_db 
**
** Connect to $dbname
*/

function    connection_db($dbname)
{
    $servername = "localhost";
    $username = "root";
    $password = "admin";

    try
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET NAMES 'UTF8'");
        return $conn;
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

/*
** get_rowid
** 
** Return the rowid of the $table where $where = $id
*/

function    get_rowid($id, $table, $where, $conn)
{
    $query = "SELECT rowid FROM ".$table." WHERE ".$where."='$id'";
    $ret = $conn->prepare($query);
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    if (empty($result))
        return null;
    else
        return $result[0];
}

/*
** querysql
**
** execute the query
*/

function    querysql($conn, $query)
{
    if (!$conn->query($query))
    {
        echo "query error";
        return 0;
    }
    else
    {
        echo "query success\n";
        return 1;
    }
}
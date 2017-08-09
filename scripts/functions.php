<?php

function    connection_db($dbname)
{
    $servername = "localhost";
    $username = "root";
    $password = "admin";

    try
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function    querysql($conn, $query)
{
    if (!$conn->query($query))
    {
        echo "query error";
        return 0;
    }
    else
    {
        echo "query success";
        return 1;
    }
}
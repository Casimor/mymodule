<?php

include_once 'functions.php';

function 	get_parent_id($conn, $id)
{
	$parent_id = $conn->prepare("SELECT rowid FROM llx_categorie WHERE id_ext='$id'");
	$parent_id->execute();
	$result = $parent_id->fetchAll(PDO::FETCH_COLUMN, 0);
	return $result[0];
}

function 	categorie_children($info, $conn)
{
	$id_ext = $info['category_id'];
	if ($info['parent_id'] == 1)
		$fk_parent = 0;
	else
		$fk_parent = get_parent_id($conn, $info['parent_id']);
	$label = $info['name'];

	$query = "INSERT INTO llx_categorie (id_ext, visible, fk_parent, label, type) VALUES ('$id_ext', 0, '$fk_parent', '$label', 0)";

	querysql($conn, $query);
}

function 	get_ids($user, $key)
{
	$conn = connection_db("magento");
	$id_col = $conn->prepare("SELECT entity_id from catalog_category_flat_store_1");
	$id_col->execute();
	$result = $id_col->fetchAll(PDO::FETCH_COLUMN, 0);
	$conn = null;
	return $result;
}

function 	get_categories($client, $sessionId, $apiUser, $apiKey)
{
	$ids = get_ids($apiUser, $apiKey);
	$conn = connection_db("dolibarr");
	querysql($conn, "ALTER TABLE llx_categorie AUTO_INCREMENT = 1");
	foreach ($ids as $index => $id)
	{
		if ($index == 0)
			continue ;
  		$infos = $client->catalogCategoryInfo($sessionId, $id);
    	$info = get_object_vars($infos);
		categorie_children($info, $conn);
	}
	$conn = null;
}
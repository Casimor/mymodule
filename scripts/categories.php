<?php

include_once 'functions.php';

/*
** Import categories from Magento to Dolibarr
** Fill llx_categorie
*/

function 	get_ids($client, $sessionId)
{
	$root = (array) $client->catalogCategoryTree($sessionId);
  	$infos = (array) $client->catalogCategoryInfo($sessionId, $root['category_id']);
  	$ids = explode(',', $infos['all_children']);
  	sort($ids);
  	return $ids;
}

function 	insert_categorie($info, $conn)
{
	$id_ext = $info['category_id'];
	$label = str_replace("'", " ", $info['name']);
	if ($info['parent_id'] == 1)
		$fk_parent = 0;
	else
		$fk_parent = get_rowid($info['parent_id'], "llx_categorie", "id_ext", $conn);

	if (!isset($fk_parent))
		return $id_ext;
	else
	{
		$query = "INSERT INTO llx_categorie (id_ext, visible, fk_parent, label, type) VALUES ('$id_ext', 0, '$fk_parent', '$label', 0)";

		querysql($conn, $query);
		return 0;
	}
}

function 	get_categories($client, $sessionId)
{
	$tab = array();
	$ids = get_ids($client, $sessionId);
	$conn = connection_db("dolibarr");
	querysql($conn, "ALTER TABLE llx_categorie AUTO_INCREMENT = 1");
	foreach ($ids as $index => $id)
	{
		if ($index == 0)
			continue ;
	  	$info = (array) $client->catalogCategoryInfo($sessionId, $id);
		$tab[] = insert_categorie($info, $conn);
	}
	foreach ($tab as $index => $id) {
		if ($id == 0)
			continue ;
		$info = (array) $client->catalogCategoryInfo($sessionId, $id);
		insert_categorie($info, $conn); 
	}
	$conn = null;
}
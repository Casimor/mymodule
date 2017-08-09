<?php
$req_categories = $bdd_magento->query('SELECT entity_id, parent_id, position, level, name FROM catalog_category_flat_store_1');

$lesCategories = array();

while($ligne = $req_categories->fetch(PDO::FETCH_ASSOC)){
    if($ligne['entity_id'] == 0)
        continue;
    $categorie = array(
                        "parent_id" => $ligne['parent_id'],
                        "position" => $ligne['position'],
                        "level" => $ligne['level'],
                        "name" => $ligne['name']
                        );
    $lesCategories[$ligne['entity_id']] = $categorie;    
}
    
$req_insert_categories = $bdd->prepare('INSERT INTO llx_categorie (entity, label, type, visible, id_ext, id_parent_ext)
                                            VALUES (1, :name, 0, 0, :id_ext, :id_parent_ext)');
foreach($lesCategories as $category_id => $values){
    $req_insert_categories->execute(array(
        'name' => $values['name'],
        'id_ext' => $category_id,
        'id_parent_ext' => $values['parent_id']
        ));
}

$magento_category_id = array();
$dolibarr_category_id = array();
$magento_parent_id = array();
$dolibarr_parent_id = array();

foreach($lesCategories as $k => $v){
    array_push($magento_category_id, $k);
    array_push($magento_parent_id, $v['parent_id']);

    // Récupère rowid (category_id de dolibarr)
    $req_dolibarr_category_id = $bdd->prepare('SELECT rowid FROM llx_categorie WHERE id_ext = :magento_category_id');
    $req_dolibarr_category_id->execute(array('magento_category_id' => $k));
    $donnee = $req_dolibarr_category_id->fetch(PDO::FETCH_ASSOC);
    array_push($dolibarr_category_id, $donnee['rowid']);

    // Récupère rowid qui correspond à un parent
    if($v['parent_id'] == 0){
        $data['rowid'] = 0;
    }
    else{
        $req_dolibarr_parent_id = $bdd->prepare('SELECT rowid FROM llx_categorie WHERE id_ext = :magento_parent_id');
        $req_dolibarr_parent_id->execute(array('magento_parent_id' => $v['parent_id']));
        $data = $req_dolibarr_parent_id->fetch(PDO::FETCH_ASSOC);
    }
    array_push($dolibarr_parent_id, $data['rowid']);
}

for($i=0; $i<count($dolibarr_category_id); $i++){
    $req_maj_categories = $bdd->prepare('UPDATE llx_categorie SET fk_parent = :dolibarr_parent_id WHERE rowid = :rowid');
    $req_maj_categories->execute(array(
        'dolibarr_parent_id' => $dolibarr_parent_id[$i],
        'rowid' => $dolibarr_category_id[$i]));
}
?>
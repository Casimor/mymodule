<?php

//WIP

function    get_rowid_country($id, $conn)
{
    $ret = $conn->prepare("SELECT rowid FROM llx_c_country WHERE code='$id'");
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    return $result[0];
}

function    get_rowid_region($id, $conn)
{
    $ret = $conn->prepare("SELECT rowid FROM llx_c_departements WHERE nom='$id'");
    $ret->execute();
    $result = $ret->fetchAll(PDO::FETCH_COLUMN, 0);
    return $result[0];
}

function    create_client($data, $client, $sessionId, $conn)
{
    $info = (array) $data['shipping_address'];
    $nom = $data['customer_firstname'].' '.$data['customer_lastname'];
    //ref_ext
    //code_client
    $email = $data['customer_email'];
    $address = $info['street'];
    $zip = $info['postcode'];
    $city = $info['city'];
    $region = $info['region'];
    $country_code = $info['country_id'];
    $telephone = $info['telephone'];
    $department_id = get_rowid_region($region, $conn);
    $country_id = get_rowid_country($country_code, $conn);
    

    echo $nom.' '.$email.' '.$address.' '.$zip.' '.$city.' '.$region.' '.$country_code.' '.$telephone.' '.$department_id.' '.$country_id;

    //$req_insert_customers = $conn->prepare('INSERT INTO llx_societe 
    //        (nom, ref_ext, code_client, address, zip, town, fk_departement, fk_pays, phone, email, fk_typent, client, //tva_assuj) 
    //        VALUES (:nom, :ref_ext, :code_client, :address, :zip, :town, :fk_departement, :fk_pays, :phone, :email, 8, 1, 1)');

}

<?php



function    create_client($data, $client, $sessionId, $conn)
{
    $info = (array) $data['shipping_address'];
    $nom = $data['customer_firstname'].' '.$data['customer_lastname'];
    //ref_ext
    //code_client
    $address = $info['street'];
    $zip = $info['postcode'];
    $req_insert_customers = $conn->prepare('INSERT INTO llx_societe 
            (nom, ref_ext, code_client, address, zip, town, fk_departement, fk_pays, phone, email, fk_typent, client, tva_assuj) 
            VALUES (:nom, :ref_ext, :code_client, :address, :zip, :town, :fk_departement, :fk_pays, :phone, :email, 8, 1, 1)');
        $req_insert_customers->execute(array(
            'nom' => $nom,
            'ref_ext' => $customer_id,
            'code_client' => $customer_id,
            'address' => $street,
            'zip' => $zip,
            'town' => $city,
            'fk_departement' => $department_id,
            'fk_pays' => $country_id,
            'phone' => $telephone,
            'email' => $email)
        );
}

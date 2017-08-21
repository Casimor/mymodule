<?php

include_once 'functions.php';

function    customer_info($client, $sessionId, $customer_id)
{
        $customerInfo = $client->customerCustomerInfo($sessionId, $customer_id);
        $customerInfo = (array) $customerInfo;
        return $customerInfo['created_at'];  // return into datec
}

function    get_customers($client, $sessionId)
{
    $conn = connection_db("dolibarr");
    $customersList = $client->customerCustomerList($sessionId);
    querysql($conn, "ALTER TABLE llx_societe AUTO_INCREMENT = 1");

    foreach($customersList as $customer) 
    {
        $customer = (array) $customer;
        $firstname = $customer['firstname'];
        $lastname = $customer['lastname'];
        $nom = $firstname.' '.$lastname;
        $customer_id = $customer['customer_id'];  // insert into ref_ext
        $email = $customer['email'];
        $created_at = customer_info($client, $sessionId, $customer_id);  // insert into datec
  
        $customerRet = $client->customerAddressList($sessionId, $customer_id);
        
        $customerAddress = (array) $customerRet[0];
        $street = $customerAddress['street'];  // insert into address
        $zip = $customerAddress['postcode'];
        $city = $customerAddress['city'];  // insert into town
        $region = $customerAddress['region'];
        $country_code = $customerAddress['country_id'];
        $telephone = $customerAddress['telephone'];

        $department_id = get_rowid($region, "llx_c_departements", "nom", $conn);
        $country_id = get_rowid($country_code, "llx_c_country", "code", $conn);

        // fk_typent = 8 
        // client = 1

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
}


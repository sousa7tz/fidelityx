<?php

namespace App\Models;

class MerchantModel{
    private $db;

    function __construct($db){
        $this->db = $db;
    }

    public function register($data){
        $sql = "INSERT INTO merchants (owner_name, store_name, address, state, city, email, phone, category, cpf, cnpj, password_hash) VALUES (:on, :sn, :address, :state, :city, :email, :phone, :category, :cpf, :cnpj, :password_hash)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
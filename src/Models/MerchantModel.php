<?php

namespace App\Models;

class MerchantModel{
    private $db;

    function __construct($db){
        $this->db = $db;
    }

    public function create($data){
        $sql = "INSERT INTO merchants (owner_name, store_name, address, state, city, email, phone, category, cpf, cnpj, password_hash) VALUES (:on, :sn, :address, :state, :city, :email, :phone, :category, :cpf, :cnpj, :password_hash)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function findByEmail($email){
        $sql = 'SELECT id, owner_name, store_name, password_hash, status FROM merchants WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $email,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC); // array associativo
    }
}
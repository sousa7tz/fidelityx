<?php 


namespace App\Controllers;
class MerchantController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function renderLogin() {
        require_once __DIR__ . '/../../views/auth/merchant/login.php';
    }

    public function renderRegister() {
        require_once __DIR__ . '/../../views/auth/merchant/register.php';
    }

}


?>
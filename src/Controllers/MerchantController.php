<?php 


namespace App\Controllers;
class MerchantController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function renderLogin() {
        // explicação geral no método renderRegister().
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }
        
        require_once __DIR__ . '/../../views/auth/merchant/login.php';

    }

    public function renderRegister() {
        // a array $_SERVER guarda tudo que vem na requisição.
        // se for POST, é porque o usuário clicou o botão (está tentando registrar).
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister(); // então, joga pro método de registro.
            return;
        }
        // se o método não for POST, vai ser GET, só carrega ou recarrega a página normalmente. 
        require_once __DIR__ . '/../../views/auth/merchant/register.php';


    }

}


?>
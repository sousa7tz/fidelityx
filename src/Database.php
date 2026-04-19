<?php
// issue no. #3
namespace App;

use PDO;
use PDOException;

class Database {
    // onde a conexao fica guardada
    private static $instance = null;

    // a funcao geral pra pegar o banco
    public static function getConnection() {
        // se ainda nao tem conexao, cria uma
        if (self::$instance === null) {
            try {
                // sets do .env
                $host = $_ENV['DB_HOST'];
                $db   = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];

                // configs de seguranca do pdo
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                // self vai no que é estatico.

                // pra nao precisar ficar chamando objeto toda hora,
                // ja pega os valores prontos.

                // aqui usamos pra pegar os dados estaticos e guardar na $instance
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4", 
                    $user, 
                    $pass, 
                    $options
                );
            } catch (PDOException $e) {
                die("Erro no banco: " . $e->getMessage());
            }
        }

        // entrega a conexão pronta pra uso
        return self::$instance;
    }
}
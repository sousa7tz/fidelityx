<?php
// arquivo vendor/autoload.php é gerado pelo composer.  
// faz com que todas as bibliotecas que instalarmos funcionem automaticamente.
require_once __DIR__ . '/../vendor/autoload.php';

// biblioteca dotenv pra ler o arquivo .env
use Dotenv\Dotenv;

// criando instancia da biblioteca dotenv. 
// __DIR__ . '/..' diz para o PHP subir uma pasta para encontrar o arquivo .env na raiz.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

// load() lê a env e guarda as informações na memoria
$dotenv->load();

try {
    // $_ENV é uma array que guarda tudo o que está na env
    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];

    // $options define como o banco vai se comportar
    $options = [
        // se der erro no sql, php avisa
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // transforma os dados do banco em arrays
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // segurança contra sql injection.
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // conexão com o banco
    // criando o objeto $pdo para usar em todo o projeto para dar comandos no banco
    $db_connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
    
} catch (\PDOException $e) {
    // erro na conexão com o banco
    die("Erro na conexão com o banco: " . $e->getMessage());
}
<?php
// O arquivo vendor/autoload.php é gerado pelo Composer. 
// Ele faz com que todas as bibliotecas que instalarmos funcionem automaticamente.
require_once __DIR__ . '/../vendor/autoload.php';

// Aqui dizemos que queremos usar a biblioteca Dotenv para ler o arquivo .env
use Dotenv\Dotenv;

// Criamos uma "instância" (uma cópia viva) da biblioteca. 
// O __DIR__ . '/..' diz para o PHP subir uma pasta para achar o arquivo .env na raiz.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

// O método load() efetivamente lê o arquivo e coloca as senhas na memória do computador.
$dotenv->load();

try {
    // $_ENV é uma "supervariável" do PHP que guarda tudo o que está no seu .env
    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];

    // O $options define COMO o banco vai se comportar.
    $options = [
        // Se der erro no SQL, o PHP avisa na hora (muito útil para você aprender).
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Transforma os dados do banco em Arrays (listas) fáceis de ler no PHP.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Segurança extra contra ataques de hackers (SQL Injection).
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Aqui é onde a conexão acontece de verdade. 
    // Criamos o objeto $pdo, que você vai usar em todo o projeto para dar comandos no banco.
    $db_connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
    
} catch (\PDOException $e) {
    // Se o banco estiver desligado ou a senha errada, ele para tudo e mostra o erro aqui.
    die("Erro na conexão com o banco: " . $e->getMessage());
}
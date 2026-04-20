<?php

// inicializacao
session_start();

// autoloading psr-4 , carrega as classes necessárias, não tem necessidade de ficar puxando com require, include, etc.
// pro nosso caso especifico, substitui massivamente os requires, deixa o código mais limpo e combinado com o singleton
// aumenta muito o desempenho e otimizaçao pra larga escala.

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// criando instancia da biblioteca dotenv
// __DIR__ . '/..' diz para o php subir uma pasta para encontrar o .env na raiz
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');

// load() lê a env e guarda as informações na memoria ($_ENV)
$dotenv->load();
// a .env será usada no Database.php!

// namespace 
use App\Database;

// conexao com o banco
$db = Database::getConnection();

// sistema tratamento da url. exemplo resultado final: http://localhost:8080/index.php?url=merchant/register
$url = $_GET['url'] ?? 'home';
$url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$domain = $urlParts[0];
$action = $urlParts[1] ?? 'dashboard';

switch ($domain) {
    
    case 'customer':
        // exemplo do psr-4 citado acima, sem require_once
        $controller = new \App\Controllers\CustomerController($db);
        
        // match é o novo switch do php, disponivel a partir do php8, deixa o codigo mais limpo
        match ($action) {
            'newPoint' => $controller->renderNewPoint(),
            'prize'    => $controller->renderPrize(),
            'redeem'   => $controller->renderRedeemPrize(),
            default    => $controller->renderDashboard(),
        };
        break;

    case 'merchant':
        $controller = new \App\Controllers\MerchantController($db);

        match ($action) {
            'login'    => $controller->renderLogin(),
            'register' => $controller->renderRegister(),
            'score'    => $controller->renderScore(),
            'insights' => $controller->renderInsights(),
            'profile'  => $controller->renderProfile(),
            'dashboard'=> $controller->renderDashboard(),
            default    => (new \App\Controllers\ErrorController())->handle(404),
        };
        break;

    case 'api':
        // em breve
        break;

    default:
        // rota inexistente é 404.
        $controller = new \App\Controllers\ErrorController();
        $controller->handle(404);
        break;
}
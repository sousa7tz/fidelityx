<?php

// 1. loading db
session_start();
require_once __DIR__ . '/../config/db.php';


// 2. the "way" of traffic

    // url filter | ex: fidelityx.com.br/merchant/dashboard = ['merchant', 'dashboard']
$url = $_GET['url'] ?? 'home';
$urlParts = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

// 3. views

$domain = $urlParts[0]; // select one of the down cases

switch ($domain) {
    
    case 'customer':
        $action = $urlParts[1] ?? 'dashboard';
        switch ($action) {
            case 'newPoint':
                require_once __DIR__ . '/../src/Controllers/CustomerController.php';
                $controller = new CustomerController($db_connection);
                $controller->renderNewPoint();
                break;
            case 'dashboard':
                require_once __DIR__ . '/../src/Controllers/CustomerController.php';
                $controller = new CustomerController($db_connection);
                $controller->renderDashboard();
                break;
            case 'prize':
                require_once __DIR__ . '/../src/Controllers/CustomerController.php';
                $controller = new CustomerController($db_connection);
                $controller->renderPrize();
                break;
            case 'redeem':
                require_once __DIR__ . '/../src/Controllers/CustomerController.php';
                $controller = new CustomerController($db_connection);
                $controller->renderRedeemPrize();
                break;
            default:
                echo "Página não encontrada :( - (404)";
        }
        break;


    case 'merchant':
        $action = $urlParts[1];
        switch ($action) {
            case 'login':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderLogin();
                break;
            case 'register':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderRegister();
                break;
            case 'dashboard':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderDashboard();
                break;
            case 'score':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderScore();
                break;
            case 'insights':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderInsights();
                break;
            case 'profile':
                require_once __DIR__ . '/../src/Controllers/MerchantController.php';
                $controller = new MerchantController($db_connection);
                $controller->renderProfile();
                break;
            default:
                
                require_once __DIR__ . '/../src/Controllers/ErrorController.php';

                $controller = new \App\Controllers\ErrorController();
                $controller->handle(404);
                break;
        }
        break;
    
    case 'api':
        // soon
        break;

    default:
        require_once __DIR__ . '/../src/Controllers/ErrorController.php';
        $controller = new \App\Controllers\ErrorController();
        $controller->handle(404);
        break;
}
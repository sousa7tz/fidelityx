<?php

namespace App\Controllers;
class ErrorController {
    public function notFound(){ //ERRO 404
        http_response_code(404);
        require_once __DIR__ . '/../../views/errors/404.php';
        exit;
    }
}
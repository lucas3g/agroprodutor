<?php

require __DIR__ . '/../vendor/autoload.php';

use Agroprodutor\Controllers\DanfeController;
use Agroprodutor\Controllers\AgroProdutorController;

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $uri);
$method = $_SERVER['REQUEST_METHOD'];

// Rotas com prefixo: /agroprodutor/... ou /danfe/...
// parts[0] = 'agroprodutor' ou 'danfe'
// parts[1+] = parâmetros da rota

// Rota: POST /danfe/{apelido}/{moduleName}/gerar
if ($parts[0] === 'danfe' && isset($parts[1]) && isset($parts[2]) && isset($parts[3]) && $parts[3] === 'gerar' && $method === 'POST') {
    DanfeController::gerarPdfs($parts[1], $parts[2]);
    exit;
}

// Rota: GET /danfe/{apelido}/{moduleName}/pdf/{clienteId}
if ($parts[0] === 'danfe' && isset($parts[1]) && isset($parts[2]) && isset($parts[3]) && $parts[3] === 'pdf' && isset($parts[4]) && $method === 'GET') {
    DanfeController::downloadPdf($parts[1], $parts[2], $parts[4]);
    exit;
}

// Rotas /agroprodutor/{apelido}/{moduleName}/...
if ($parts[0] === 'agroprodutor' && isset($parts[1]) && isset($parts[2]) && isset($parts[3])) {
    $apelido = $parts[1];
    $moduleName = $parts[2];
    $action = $parts[3];

    // POST /agroprodutor/{apelido}/{moduleName}/setjson/{campoChave?}
    if ($action === 'setjson' && $method === 'POST') {
        $campoChave = isset($parts[4]) ? $parts[4] : 'CLIFOR';
        AgroProdutorController::setJson($apelido, $moduleName, $campoChave);
        exit;
    }

    // GET /agroprodutor/{apelido}/{moduleName}/getjson/{jsonName?}
    if ($action === 'getjson' && $method === 'GET') {
        $jsonName = isset($parts[4]) ? $parts[4] : '';
        AgroProdutorController::getJson($apelido, $moduleName, $jsonName);
        exit;
    }

    // POST /agroprodutor/{apelido}/{moduleName}/register
    if ($action === 'register' && $method === 'POST') {
        AgroProdutorController::register($apelido, $moduleName);
        exit;
    }

    // POST /agroprodutor/{apelido}/{moduleName}/validate
    if ($action === 'validate' && $method === 'POST') {
        AgroProdutorController::validateCredentials($apelido, $moduleName);
        exit;
    }
}

http_response_code(404);
echo 'Rota não encontrada';

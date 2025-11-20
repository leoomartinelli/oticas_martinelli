<?php
// Cabeçalhos CORS (Permitir acesso externo se for fazer front-end separado)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Pega a URL amigável
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Roteamento Básico
$recurso = $urlParts[0]; // Ex: 'clientes'
$id = isset($urlParts[1]) ? $urlParts[1] : null; // Ex: 1

// Define o método HTTP (GET, POST, etc)
$method = $_SERVER['REQUEST_METHOD'];

// Roteador
switch ($recurso) {
    case 'clientes':
        include_once 'controllers/ClienteController.php';
        $controller = new ClienteController();

        if ($method == 'GET') {
            if (isset($urlParts[1]) && is_numeric($urlParts[1])) {
                $controller->show($urlParts[1]);
            } else {
                $controller->index();
            }
        } elseif ($method == 'POST') {
            $controller->store();
        }
        // ADICIONE ESTE BLOCO SE NÃO TIVER:
        elseif ($method == 'PUT' && isset($urlParts[1])) {
            $controller->update($urlParts[1]);
        }
        // ----------------------------------
        elseif ($method == 'DELETE' && isset($urlParts[1])) {
            $controller->delete($urlParts[1]);
        }
        break;

    case 'receitas':
        include_once 'controllers/ReceitaController.php';
        $receitaController = new ReceitaController();

        if ($method == 'POST') {
            $receitaController->store();
        }
        // GET: Buscar por ID (Ex: receitas/5)
        elseif ($method == 'GET' && isset($urlParts[1]) && is_numeric($urlParts[1])) {
            $receitaController->show($urlParts[1]);
        }
        // GET: Buscar por Cliente
        elseif ($method == 'GET' && isset($urlParts[1]) && $urlParts[1] == 'cliente') {
            $idCliente = $urlParts[2];
            $receitaController->getByCliente($idCliente);
        }
        // PUT: Atualizar (Ex: receitas/5)
        elseif ($method == 'PUT' && isset($urlParts[1])) {
            $receitaController->update($urlParts[1]);
        }
        // DELETE: Apagar (Ex: receitas/5)
        elseif ($method == 'DELETE' && isset($urlParts[1])) {
            $receitaController->delete($urlParts[1]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["mensagem" => "Rota não encontrada"]);
        break;
}
?>
<?php
// Configurações de CORS (Permitir acesso do navegador)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Se for uma requisição OPTIONS (pre-flight do navegador), para por aqui
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Pega a URL amigável
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Roteamento Básico
$recurso = $urlParts[0]; // Ex: 'clientes' ou 'receitas'
$id = isset($urlParts[1]) ? $urlParts[1] : null; // Ex: 1

// Define o método HTTP (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Roteador Principal
switch ($recurso) {
    case 'clientes':
        include_once 'controllers/ClienteController.php';
        $controller = new ClienteController();

        if ($method == 'GET') {
            if ($id && is_numeric($id)) {
                $controller->show($id); // Buscar um
            } else {
                $controller->index();   // Listar todos ou buscar
            }
        } elseif ($method == 'POST') {
            $controller->store();       // Criar
        } elseif ($method == 'PUT' && $id) {
            $controller->update($id);   // Atualizar
        } elseif ($method == 'DELETE' && $id) {
            $controller->delete($id);   // Deletar
        }
        break;

    case 'receitas':
        // === A CORREÇÃO ESTÁ AQUI: INCLUIR O ARQUIVO ===
        include_once 'controllers/ReceitaController.php';
        $receitaController = new ReceitaController();

        if ($method == 'GET') {
            // Se a URL for /receitas/cliente/5
            if ($id == 'cliente' && isset($urlParts[2])) {
                $idCliente = $urlParts[2];
                $receitaController->getByCliente($idCliente);
            }
            // Se a URL for /receitas/5 (buscar uma específica)
            elseif ($id && is_numeric($id)) {
                $receitaController->show($id);
            }
        } elseif ($method == 'POST') {
            $receitaController->store(); // Criar
        } elseif ($method == 'PUT' && $id) {
            $receitaController->update($id); // Atualizar
        } elseif ($method == 'DELETE' && $id) {
            $receitaController->delete($id); // Deletar
        }
        break;

    case 'pedidos-lente':
        include_once 'controllers/PedidoLenteController.php';
        $pedController = new PedidoLenteController();

        if ($method == 'POST') {
            $pedController->store();
        } elseif ($method == 'PUT' && $id) {
            // Atualizar status: PUT /pedidos-lente/{id}
            $pedController->updateStatus($id);
        } elseif ($method == 'GET' && isset($urlParts[2]) && $urlParts[1] == 'cliente') {
            $idCliente = $urlParts[2];
            $pedController->getByCliente($idCliente);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["mensagem" => "Rota não encontrada"]);
        break;
}
?>
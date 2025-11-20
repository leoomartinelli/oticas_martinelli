<?php
include_once './config/Database.php';
include_once './models/Cliente.php';

class ClienteController
{
    private $db;
    private $cliente;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cliente = new Cliente($this->db);
    }

    public function index()
    {
        if (isset($_GET['busca']) && !empty($_GET['busca'])) {
            $stmt = $this->cliente->search($_GET['busca']);
        } else {
            $stmt = $this->cliente->read();
        }
        $num = $stmt->rowCount();
        if ($num > 0) {
            $clientes_arr = array();
            $clientes_arr["dados"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $cliente_item = array("id" => $id, "nome" => $nome, "cpf" => $cpf, "telefone" => $telefone, "email" => $email);
                array_push($clientes_arr["dados"], $cliente_item);
            }
            http_response_code(200);
            echo json_encode($clientes_arr);
        } else {
            http_response_code(200);
            echo json_encode(array("dados" => []));
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nome)) {
            $this->cliente->nome = $data->nome;
            $this->cliente->data_nascimento = $data->data_nascimento;
            $this->cliente->cpf = $data->cpf;
            $this->cliente->rg = $data->rg;
            $this->cliente->email = $data->email;
            $this->cliente->telefone = $data->telefone;
            $this->cliente->cep = $data->cep;
            $this->cliente->endereco = $data->endereco;
            $this->cliente->numero = $data->numero; // <--- NOVO
            $this->cliente->bairro = $data->bairro;
            $this->cliente->cidade = $data->cidade;

            if ($this->cliente->create()) {
                http_response_code(201);
                echo json_encode(array("mensagem" => "Cliente criado."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensagem" => "Erro ao criar."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensagem" => "Dados incompletos."));
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->cliente->id = $id;
        $this->cliente->nome = $data->nome;
        $this->cliente->data_nascimento = $data->data_nascimento;
        $this->cliente->cpf = $data->cpf;
        $this->cliente->rg = $data->rg;
        $this->cliente->email = $data->email;
        $this->cliente->telefone = $data->telefone;
        $this->cliente->cep = $data->cep;
        $this->cliente->endereco = $data->endereco;
        $this->cliente->numero = $data->numero; // <--- NOVO
        $this->cliente->bairro = $data->bairro;
        $this->cliente->cidade = $data->cidade;

        if ($this->cliente->update()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Atualizado."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro."));
        }
    }

    public function delete($id)
    {
        $this->cliente->id = $id;
        if ($this->cliente->delete()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Deletado."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro."));
        }
    }

    public function show($id)
    {
        $this->cliente->id = $id;
        $stmt = $this->cliente->readOne();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            http_response_code(200);
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(array("mensagem" => "Não encontrado."));
        }
    }
}
?>
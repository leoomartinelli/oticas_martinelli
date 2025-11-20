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

    // GET: Listar clientes
    public function index()
    {
        // Verifica se foi passado ?busca=algumacoisa na URL
        if (isset($_GET['busca']) && !empty($_GET['busca'])) {
            $stmt = $this->cliente->search($_GET['busca']);
        } else {
            // Se não tiver busca, traz todos
            $stmt = $this->cliente->read();
        }

        $num = $stmt->rowCount();

        if ($num > 0) {
            $clientes_arr = array();
            $clientes_arr["dados"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $cliente_item = array(
                    "id" => $id,
                    "nome" => $nome,
                    "cpf" => $cpf,
                    "telefone" => $telefone,
                    "email" => $email
                );
                array_push($clientes_arr["dados"], $cliente_item);
            }
            http_response_code(200);
            echo json_encode($clientes_arr);
        } else {
            // Retorna array vazio ao invés de erro 404 para a busca não quebrar o JS
            http_response_code(200);
            echo json_encode(array("dados" => []));
        }
    }

    // POST: Criar cliente
    public function store()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->nome) && !empty($data->cpf)) {
            $this->cliente->nome = $data->nome;
            $this->cliente->data_nascimento = $data->data_nascimento;
            $this->cliente->cpf = $data->cpf;
            $this->cliente->rg = $data->rg;
            $this->cliente->email = $data->email;
            $this->cliente->telefone = $data->telefone;
            $this->cliente->cep = $data->cep;
            $this->cliente->endereco = $data->endereco;

            if ($this->cliente->create()) {
                http_response_code(201);
                echo json_encode(array("mensagem" => "Cliente criado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensagem" => "Não foi possível criar o cliente."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensagem" => "Dados incompletos."));
        }
    }

    // DELETE
    public function delete($id)
    {
        $this->cliente->id = $id;
        if ($this->cliente->delete()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Cliente deletado."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Não foi possível deletar."));
        }
    }

    public function show($id)
    {
        $this->cliente->id = $id;

        // Precisamos criar o readOne no Model também? Sim. 
        // Mas podemos usar uma query direta aqui ou adicionar no Model.
        // Vamos adicionar no Model para ficar padrão.
        $stmt = $this->cliente->readOne();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            http_response_code(200);
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(array("mensagem" => "Cliente não encontrado."));
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

        if ($this->cliente->update()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Cliente atualizado."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro ao atualizar."));
        }
    }
}
?>
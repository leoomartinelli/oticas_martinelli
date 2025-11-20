<?php
include_once './config/Database.php';
include_once './models/Receita.php';

class ReceitaController
{
    private $db;
    private $receita;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->receita = new Receita($this->db);
    }

    // Criar nova receita
    public function store()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->cliente_id) && !empty($data->data_receita)) {
            // Mapear JSON para o Objeto
            $this->receita->cliente_id = $data->cliente_id;
            $this->receita->data_receita = $data->data_receita;
            $this->receita->medico = $data->medico;

            // Longe OD
            $this->receita->longe_od_esferico = $data->longe_od_esferico;
            $this->receita->longe_od_cilindrico = $data->longe_od_cilindrico;
            $this->receita->longe_od_eixo = $data->longe_od_eixo;
            $this->receita->longe_od_dnp = $data->longe_od_dnp;

            // Longe OE
            $this->receita->longe_oe_esferico = $data->longe_oe_esferico;
            $this->receita->longe_oe_cilindrico = $data->longe_oe_cilindrico;
            $this->receita->longe_oe_eixo = $data->longe_oe_eixo;
            $this->receita->longe_oe_dnp = $data->longe_oe_dnp;

            // Perto OD
            $this->receita->perto_od_esferico = $data->perto_od_esferico;
            $this->receita->perto_od_cilindrico = $data->perto_od_cilindrico;
            $this->receita->perto_od_eixo = $data->perto_od_eixo;
            $this->receita->perto_od_dnp = $data->perto_od_dnp;

            // Perto OE
            $this->receita->perto_oe_esferico = $data->perto_oe_esferico;
            $this->receita->perto_oe_cilindrico = $data->perto_oe_cilindrico;
            $this->receita->perto_oe_eixo = $data->perto_oe_eixo;
            $this->receita->perto_oe_dnp = $data->perto_oe_dnp;

            $this->receita->lentes_desc = $data->lentes_desc;
            $this->receita->observacoes = $data->observacoes;

            if ($this->receita->create()) {
                http_response_code(201);
                echo json_encode(array("mensagem" => "Receita salva com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("mensagem" => "Erro ao salvar receita."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensagem" => "Dados incompletos. Cliente ID e Data são obrigatórios."));
        }
    }

    // Buscar receitas de um cliente específico
    public function getByCliente($id_cliente)
    {
        $stmt = $this->receita->readByClient($id_cliente);
        $num = $stmt->rowCount();

        if ($num > 0) {
            $receitas_arr = array();
            $receitas_arr["dados"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Pega todos os dados da linha
                array_push($receitas_arr["dados"], $row);
            }
            http_response_code(200);
            echo json_encode($receitas_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("mensagem" => "Nenhuma receita encontrada para este cliente."));
        }
    }

    public function show($id)
    {
        // O ERRO ESTAVA AQUI: Antes estava $this->cliente->id
        // O CORRETO É: $this->receita->id
        $this->receita->id = $id;

        $stmt = $this->receita->readOne();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            http_response_code(200);
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(array("mensagem" => "Receita não encontrada."));
        }
    }

    // PUT: Atualizar
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->receita->id = $id;
        // ... Mapeie TODOS os campos igual no store() ...
        // Para economizar espaço aqui, copie o bloco de mapeamento do store() 
        // (longe_od_esferico, etc...) e cole aqui.

        // Vou colocar apenas o inicio do mapeamento como exemplo:
        $this->receita->data_receita = $data->data_receita;
        $this->receita->medico = $data->medico;
        $this->receita->lentes_desc = $data->lentes_desc;
        $this->receita->observacoes = $data->observacoes;

        $this->receita->longe_od_esferico = $data->longe_od_esferico;
        $this->receita->longe_od_cilindrico = $data->longe_od_cilindrico;
        $this->receita->longe_od_eixo = $data->longe_od_eixo;
        $this->receita->longe_od_dnp = $data->longe_od_dnp;

        $this->receita->longe_oe_esferico = $data->longe_oe_esferico;
        $this->receita->longe_oe_cilindrico = $data->longe_oe_cilindrico;
        $this->receita->longe_oe_eixo = $data->longe_oe_eixo;
        $this->receita->longe_oe_dnp = $data->longe_oe_dnp;

        $this->receita->perto_od_esferico = $data->perto_od_esferico;
        $this->receita->perto_od_cilindrico = $data->perto_od_cilindrico;
        $this->receita->perto_od_eixo = $data->perto_od_eixo;
        $this->receita->perto_od_dnp = $data->perto_od_dnp;

        $this->receita->perto_oe_esferico = $data->perto_oe_esferico;
        $this->receita->perto_oe_cilindrico = $data->perto_oe_cilindrico;
        $this->receita->perto_oe_eixo = $data->perto_oe_eixo;
        $this->receita->perto_oe_dnp = $data->perto_oe_dnp;


        if ($this->receita->update()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Receita atualizada."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro ao atualizar."));
        }
    }

    // DELETE
    public function delete($id)
    {
        $this->receita->id = $id;
        if ($this->receita->delete()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Receita deletada."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro ao deletar."));
        }
    }


}
?>
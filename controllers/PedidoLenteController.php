<?php
include_once './config/Database.php';
include_once './models/PedidoLente.php';
include_once './models/Cliente.php';

class PedidoLenteController
{
    private $db;
    private $pedido;
    private $clienteModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pedido = new PedidoLente($this->db);
        $this->clienteModel = new Cliente($this->db);
    }

    // Listar por cliente
    public function getByCliente($cliente_id)
    {
        $stmt = $this->pedido->readByClient($cliente_id);
        $num = $stmt->rowCount();
        $pedidos_arr = array("dados" => array());

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($pedidos_arr["dados"], $row);
            }
        }
        http_response_code(200);
        echo json_encode($pedidos_arr);
    }

    // Criar Novo Pedido
    public function store()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->cliente_id) && !empty($data->numero_os)) {
            $this->pedido->cliente_id = $data->cliente_id;
            $this->pedido->numero_os = $data->numero_os;

            // Lentes e Medidas (igual ao anterior...)
            $this->pedido->od_esferico = $data->od_esferico;
            $this->pedido->od_cilindrico = $data->od_cilindrico;
            $this->pedido->od_eixo = $data->od_eixo;
            $this->pedido->od_dnp = $data->od_dnp;
            $this->pedido->oe_esferico = $data->oe_esferico;
            $this->pedido->oe_cilindrico = $data->oe_cilindrico;
            $this->pedido->oe_eixo = $data->oe_eixo;
            $this->pedido->oe_dnp = $data->oe_dnp;
            $this->pedido->adicao = $data->adicao;

            $this->pedido->medida_dp = $data->medida_dp;
            $this->pedido->medida_mha = $data->medida_mha;
            $this->pedido->medida_mva = $data->medida_mva;
            $this->pedido->medida_dma = $data->medida_dma;
            $this->pedido->medida_ponte = $data->medida_ponte;
            $this->pedido->medida_altura = $data->medida_altura;
            $this->pedido->tipo_armacao = $data->tipo_armacao;
            $this->pedido->descricao_lentes = $data->descricao_lentes;

            // Busca dados do cliente e envia Webhook
            $this->clienteModel->id = $data->cliente_id;
            $stmtCli = $this->clienteModel->readOne();
            $dadosCliente = $stmtCli->fetch(PDO::FETCH_ASSOC);

            // Webhook Payload
            $webhookUrl = "https://sistema-crescer-n8n.vuvd0x.easypanel.host/webhook/pedido-lente";
            $payload = array(
                "os" => $data->numero_os,
                "status_inicial" => "Lente Solicitada",
                "cliente" => ["nome" => $dadosCliente['nome'], "cpf" => $dadosCliente['cpf']],
                "receita" => [
                    "od" => ["esf" => $data->od_esferico, "cil" => $data->od_cilindrico, "eixo" => $data->od_eixo, "dnp" => $data->od_dnp],
                    "oe" => ["esf" => $data->oe_esferico, "cil" => $data->oe_cilindrico, "eixo" => $data->oe_eixo, "dnp" => $data->oe_dnp],
                    "adicao" => $data->adicao
                ],
                "medidas" => [
                    "dp" => $data->medida_dp,
                    "mha" => $data->medida_mha,
                    "mva" => $data->medida_mva,
                    "dma" => $data->medida_dma,
                    "ponte" => $data->medida_ponte,
                    "altura" => $data->medida_altura,
                    "armacao" => $data->tipo_armacao
                ],
                "lentes" => $data->descricao_lentes
            );

            $this->pedido->status_envio = $this->enviarWebhook($webhookUrl, $payload) ? "enviado" : "erro_envio";

            if ($this->pedido->create()) {
                http_response_code(201);
                echo json_encode(array("mensagem" => "Pedido criado.", "webhook" => $this->pedido->status_envio));
            } else {
                http_response_code(503);
                echo json_encode(array("mensagem" => "Erro banco."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("mensagem" => "Faltam dados."));
        }
    }

    // Atualizar Status (PUT)
    public function updateStatus($id)
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->pedido->id = $id;
        $this->pedido->status = $data->status;

        if ($this->pedido->updateStatus()) {
            http_response_code(200);
            echo json_encode(array("mensagem" => "Status atualizado para: " . $data->status));
        } else {
            http_response_code(503);
            echo json_encode(array("mensagem" => "Erro ao atualizar status."));
        }
    }

    private function enviarWebhook($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpCode >= 200 && $httpCode < 300);
    }
}
?>
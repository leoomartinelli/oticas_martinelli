<?php
class PedidoLente
{
    private $conn;
    private $table_name = "pedidos_lente";

    public $id;
    public $cliente_id;
    public $numero_os;
    public $data_pedido;

    // Dados da Lente
    public $od_esferico;
    public $od_cilindrico;
    public $od_eixo;
    public $od_dnp;
    public $oe_esferico;
    public $oe_cilindrico;
    public $oe_eixo;
    public $oe_dnp;
    public $adicao;

    // Dados da Armação
    public $medida_dp;
    public $medida_mha;
    public $medida_mva;
    public $medida_dma;
    public $medida_ponte;
    public $medida_altura;
    public $tipo_armacao;

    public $descricao_lentes;
    public $status_envio; // Status do Webhook (técnico)
    public $status;       // Status do Processo (negócio: Solicitada, Montado, Finalizado)

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        // Define o status inicial padrão
        $this->status = "Lente Solicitada";

        $query = "INSERT INTO " . $this->table_name . " SET
            cliente_id=:cliente_id, numero_os=:numero_os,
            od_esferico=:od_esferico, od_cilindrico=:od_cilindrico, od_eixo=:od_eixo, od_dnp=:od_dnp,
            oe_esferico=:oe_esferico, oe_cilindrico=:oe_cilindrico, oe_eixo=:oe_eixo, oe_dnp=:oe_dnp,
            adicao=:adicao,
            medida_dp=:medida_dp, medida_mha=:medida_mha, medida_mva=:medida_mva,
            medida_dma=:medida_dma, medida_ponte=:medida_ponte, medida_altura=:medida_altura,
            tipo_armacao=:tipo_armacao, descricao_lentes=:descricao_lentes, 
            status_envio=:status_envio, status=:status";

        $stmt = $this->conn->prepare($query);

        // Binds
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":numero_os", $this->numero_os);

        $stmt->bindParam(":od_esferico", $this->od_esferico);
        $stmt->bindParam(":od_cilindrico", $this->od_cilindrico);
        $stmt->bindParam(":od_eixo", $this->od_eixo);
        $stmt->bindParam(":od_dnp", $this->od_dnp);

        $stmt->bindParam(":oe_esferico", $this->oe_esferico);
        $stmt->bindParam(":oe_cilindrico", $this->oe_cilindrico);
        $stmt->bindParam(":oe_eixo", $this->oe_eixo);
        $stmt->bindParam(":oe_dnp", $this->oe_dnp);

        $stmt->bindParam(":adicao", $this->adicao);

        $stmt->bindParam(":medida_dp", $this->medida_dp);
        $stmt->bindParam(":medida_mha", $this->medida_mha);
        $stmt->bindParam(":medida_mva", $this->medida_mva);
        $stmt->bindParam(":medida_dma", $this->medida_dma);
        $stmt->bindParam(":medida_ponte", $this->medida_ponte);
        $stmt->bindParam(":medida_altura", $this->medida_altura);
        $stmt->bindParam(":tipo_armacao", $this->tipo_armacao);

        $stmt->bindParam(":descricao_lentes", $this->descricao_lentes);
        $stmt->bindParam(":status_envio", $this->status_envio);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute())
            return true;
        return false;
    }

    // Atualizar apenas o Status
    public function updateStatus()
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute())
            return true;
        return false;
    }

    public function readByClient($cliente_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cliente_id = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
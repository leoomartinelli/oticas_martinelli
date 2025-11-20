<?php
class Receita
{
    private $conn;
    private $table_name = "receitas";

    // Propriedades (Campos do Banco)
    public $id;
    public $cliente_id;
    public $data_receita;
    public $medico;

    // Longe OD
    public $longe_od_esferico;
    public $longe_od_cilindrico;
    public $longe_od_eixo;
    public $longe_od_dnp;
    // Longe OE
    public $longe_oe_esferico;
    public $longe_oe_cilindrico;
    public $longe_oe_eixo;
    public $longe_oe_dnp;
    // Perto OD
    public $perto_od_esferico;
    public $perto_od_cilindrico;
    public $perto_od_eixo;
    public $perto_od_dnp;
    // Perto OE
    public $perto_oe_esferico;
    public $perto_oe_cilindrico;
    public $perto_oe_eixo;
    public $perto_oe_dnp;

    public $lentes_desc;
    public $observacoes;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. CREATE (Salvar nova receita)
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET
            cliente_id=:cliente_id, data_receita=:data_receita, medico=:medico,
            longe_od_esferico=:lod_esf, longe_od_cilindrico=:lod_cil, longe_od_eixo=:lod_eixo, longe_od_dnp=:lod_dnp,
            longe_oe_esferico=:loe_esf, longe_oe_cilindrico=:loe_cil, longe_oe_eixo=:loe_eixo, longe_oe_dnp=:loe_dnp,
            perto_od_esferico=:pod_esf, perto_od_cilindrico=:pod_cil, perto_od_eixo=:pod_eixo, perto_od_dnp=:pod_dnp,
            perto_oe_esferico=:poe_esf, perto_oe_cilindrico=:poe_cil, perto_oe_eixo=:poe_eixo, perto_oe_dnp=:poe_dnp,
            lentes_desc=:lentes, observacoes=:obs";

        $stmt = $this->conn->prepare($query);

        // Bind dos parâmetros (ligando as variáveis ao SQL)
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":data_receita", $this->data_receita);
        $stmt->bindParam(":medico", $this->medico);

        // Longe
        $stmt->bindParam(":lod_esf", $this->longe_od_esferico);
        $stmt->bindParam(":lod_cil", $this->longe_od_cilindrico);
        $stmt->bindParam(":lod_eixo", $this->longe_od_eixo);
        $stmt->bindParam(":lod_dnp", $this->longe_od_dnp);
        $stmt->bindParam(":loe_esf", $this->longe_oe_esferico);
        $stmt->bindParam(":loe_cil", $this->longe_oe_cilindrico);
        $stmt->bindParam(":loe_eixo", $this->longe_oe_eixo);
        $stmt->bindParam(":loe_dnp", $this->longe_oe_dnp);

        // Perto
        $stmt->bindParam(":pod_esf", $this->perto_od_esferico);
        $stmt->bindParam(":pod_cil", $this->perto_od_cilindrico);
        $stmt->bindParam(":pod_eixo", $this->perto_od_eixo);
        $stmt->bindParam(":pod_dnp", $this->perto_od_dnp);
        $stmt->bindParam(":poe_esf", $this->perto_oe_esferico);
        $stmt->bindParam(":poe_cil", $this->perto_oe_cilindrico);
        $stmt->bindParam(":poe_eixo", $this->perto_oe_eixo);
        $stmt->bindParam(":poe_dnp", $this->perto_oe_dnp);

        // Extras
        $stmt->bindParam(":lentes", $this->lentes_desc);
        $stmt->bindParam(":obs", $this->observacoes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. UPDATE (Atualizar receita existente)
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET
            data_receita=:data_receita, medico=:medico,
            longe_od_esferico=:lod_esf, longe_od_cilindrico=:lod_cil, longe_od_eixo=:lod_eixo, longe_od_dnp=:lod_dnp,
            longe_oe_esferico=:loe_esf, longe_oe_cilindrico=:loe_cil, longe_oe_eixo=:loe_eixo, longe_oe_dnp=:loe_dnp,
            perto_od_esferico=:pod_esf, perto_od_cilindrico=:pod_cil, perto_od_eixo=:pod_eixo, perto_od_dnp=:pod_dnp,
            perto_oe_esferico=:poe_esf, perto_oe_cilindrico=:poe_cil, perto_oe_eixo=:poe_eixo, perto_oe_dnp=:poe_dnp,
            lentes_desc=:lentes, observacoes=:obs
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind ID e Dados Básicos
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":data_receita", $this->data_receita);
        $stmt->bindParam(":medico", $this->medico);

        // Bind Longe
        $stmt->bindParam(":lod_esf", $this->longe_od_esferico);
        $stmt->bindParam(":lod_cil", $this->longe_od_cilindrico);
        $stmt->bindParam(":lod_eixo", $this->longe_od_eixo);
        $stmt->bindParam(":lod_dnp", $this->longe_od_dnp);
        $stmt->bindParam(":loe_esf", $this->longe_oe_esferico);
        $stmt->bindParam(":loe_cil", $this->longe_oe_cilindrico);
        $stmt->bindParam(":loe_eixo", $this->longe_oe_eixo);
        $stmt->bindParam(":loe_dnp", $this->longe_oe_dnp);

        // Bind Perto
        $stmt->bindParam(":pod_esf", $this->perto_od_esferico);
        $stmt->bindParam(":pod_cil", $this->perto_od_cilindrico);
        $stmt->bindParam(":pod_eixo", $this->perto_od_eixo);
        $stmt->bindParam(":pod_dnp", $this->perto_od_dnp);
        $stmt->bindParam(":poe_esf", $this->perto_oe_esferico);
        $stmt->bindParam(":poe_cil", $this->perto_oe_cilindrico);
        $stmt->bindParam(":poe_eixo", $this->perto_oe_eixo);
        $stmt->bindParam(":poe_dnp", $this->perto_oe_dnp);

        // Bind Extras
        $stmt->bindParam(":lentes", $this->lentes_desc);
        $stmt->bindParam(":obs", $this->observacoes);

        if ($stmt->execute())
            return true;
        return false;
    }

    // 4. DELETE (Apagar receita)
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute())
            return true;
        return false;
    }

    // 5. READ SINGLE (Ler uma única receita para preencher o modal)
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt; // Retorna o objeto statement
    }

    // 2. READ BY CLIENT (Buscar todas as receitas de um cliente específico)
    // Isso é muito útil: "Mostre o histórico do Sr. João"
    public function readByClient($cliente_id_busca)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cliente_id = ? ORDER BY data_receita DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id_busca);
        $stmt->execute();
        return $stmt;
    }
}
?>
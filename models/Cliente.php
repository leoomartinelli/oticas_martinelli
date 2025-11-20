<?php
class Cliente
{
    private $conn;
    private $table_name = "clientes";

    public $id;
    public $nome;
    public $data_nascimento;
    public $cpf;
    public $rg;
    public $email;
    public $telefone;
    public $cep;
    public $endereco;
    public $numero; // <--- NOVO
    public $bairro;
    public $cidade;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET 
            nome=:nome, data_nascimento=:data_nascimento, cpf=:cpf, rg=:rg, 
            email=:email, telefone=:telefone, cep=:cep, 
            endereco=:endereco, numero=:numero, bairro=:bairro, cidade=:cidade"; // Adicionado numero

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":cep", $this->cep);
        $stmt->bindParam(":endereco", $this->endereco);
        $stmt->bindParam(":numero", $this->numero); // <--- Bind NOVO
        $stmt->bindParam(":bairro", $this->bairro);
        $stmt->bindParam(":cidade", $this->cidade);

        if ($stmt->execute())
            return true;
        return false;
    }

    // READ (Sem alterações)
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // UPDATE
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET 
            nome=:nome, data_nascimento=:data_nascimento, cpf=:cpf, rg=:rg, 
            email=:email, telefone=:telefone, cep=:cep, 
            endereco=:endereco, numero=:numero, bairro=:bairro, cidade=:cidade 
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":cep", $this->cep);
        $stmt->bindParam(":endereco", $this->endereco);
        $stmt->bindParam(":numero", $this->numero); // <--- Bind NOVO
        $stmt->bindParam(":bairro", $this->bairro);
        $stmt->bindParam(":cidade", $this->cidade);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute())
            return true;
        return false;
    }

    // DELETE (Sem alterações)
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute())
            return true;
        return false;
    }

    // SEARCH (Sem alterações)
    public function search($keywords)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nome LIKE ? OR cpf LIKE ? OR telefone LIKE ? ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $keywords = htmlspecialchars(strip_tags($keywords));
        $term = "%{$keywords}%";
        $stmt->bindParam(1, $term);
        $stmt->bindParam(2, $term);
        $stmt->bindParam(3, $term);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE (Sem alterações)
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>
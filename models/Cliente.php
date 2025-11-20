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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. READ (Ler todos)
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. CREATE (Criar)
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, data_nascimento=:data_nascimento, cpf=:cpf, rg=:rg, email=:email, telefone=:telefone, cep=:cep, endereco=:endereco";

        $stmt = $this->conn->prepare($query);

        // Limpar dados (segurança básica)
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        // ... faça isso para os outros campos se quiser ser rigoroso

        // Bind dos valores
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":cep", $this->cep);
        $stmt->bindParam(":endereco", $this->endereco);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. UPDATE (Atualizar)
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET 
            nome=:nome, 
            data_nascimento=:data_nascimento, 
            cpf=:cpf, 
            rg=:rg, 
            email=:email, 
            telefone=:telefone, 
            cep=:cep, 
            endereco=:endereco 
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind dos valores
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":cep", $this->cep);
        $stmt->bindParam(":endereco", $this->endereco);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 4. DELETE (Deletar)
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function search($keywords)
    {
        // A query busca se o termo aparece no nome OU cpf OU telefone
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE nome LIKE ? OR cpf LIKE ? OR telefone LIKE ? 
                  ORDER BY nome ASC";

        $stmt = $this->conn->prepare($query);

        // Limpa e adiciona os % para o LIKE funcionar (ex: %joao%)
        $keywords = htmlspecialchars(strip_tags($keywords));
        $term = "%{$keywords}%";

        // Vincula o mesmo termo aos 3 parâmetros (?)
        $stmt->bindParam(1, $term);
        $stmt->bindParam(2, $term);
        $stmt->bindParam(3, $term);

        $stmt->execute();
        return $stmt;
    }

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

<?php

class Itens {

    private $itid;
    private $nomi;
    private $nefeito;
    private $preco;
    private $descri;

    public function __construct(
        $nomi,
        $nefeito,
        $preco,
        $descri = null
    ) {
        $this->nomi = $nomi;
        $this->nefeito = $nefeito;
        $this->preco = $preco;
        $this->descri = $descri;
    }

    // Cadastra um novo item
    public function cadastrarItem() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO itens
                (nomi, nefeito, preco, descri)
                VALUES
                (:nomi, :nefeito, :preco, :descri)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nomi', $this->nomi);
        $stmt->bindValue(':nefeito', $this->nefeito);
        $stmt->bindValue(':preco', $this->preco);
        $stmt->bindValue(':descri', $this->descri);

        if ($stmt->execute()) {
            $this->itid = $pdo->lastInsertId();
            return true;
        }

        return false;
    }

    // Lista todos os itens disponíveis na loja
    public static function ListarItens() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM itens
                ORDER BY nomi ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Consulta um item específico
    public function VerItem() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM itens
                WHERE itid = :itid";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':itid', $this->itid, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return false;
        }

        $this->nomi = $dados['nomi'];
        $this->nefeito = $dados['nefeito'];
        $this->preco = $dados['preco'];
        $this->descri = $dados['descri'];

        return $dados;
    }

    // Edita as informações de um item
    public function EditarItem() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE itens
                SET nomi = :nomi,
                    nefeito = :nefeito,
                    preco = :preco,
                    descri = :descri
                WHERE itid = :itid";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nomi', $this->nomi);
        $stmt->bindValue(':nefeito', $this->nefeito);
        $stmt->bindValue(':preco', $this->preco);
        $stmt->bindValue(':descri', $this->descri);
        $stmt->bindValue(':itid', $this->itid, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Exclui um item
    public function ApagarItem() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "DELETE FROM itens
                WHERE itid = :itid";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':itid', $this->itid, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Getters
    public function getItid() {
        return $this->itid;
    }

    public function getNomi() {
        return $this->nomi;
    }

    public function getNefeito() {
        return $this->nefeito;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getDescri() {
        return $this->descri;
    }

    // Setters
    public function setItid($itid) {
        $this->itid = $itid;
    }

    public function setNomi($nomi) {
        $this->nomi = $nomi;
    }

    public function setNefeito($nefeito) {
        $this->nefeito = $nefeito;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function setDescri($descri) {
        $this->descri = $descri;
    }
}
?>
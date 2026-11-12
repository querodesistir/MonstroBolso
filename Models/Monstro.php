<?php
class Monstro
{
    private $id;
    private $nome;
    private $camimage;
    private $dano;
    private $hp;
    private $ahp; //hp atual
    private $def;
    private $prio; //prioridade
    private $danoEspecial;
    private $nodeck;
    function __construct($camimage, $nome, $dano, $hp, $ahp, $def, $prio, $danoEspecial, $nodeck)
    {
        $this->nome = $nome;
        $this->camimage = $camimage;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
        $this->prio = $prio;
        $this->danoEspecial = $danoEspecial;
        $this->nodeck = $nodeck;  //fazer set e get desse
    }

    function buscarMonstro()
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT * FROM Monstro WHERE nome like :nome";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', '%' . trim($this->nome) . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function ListarMonstroPmontarDeck()
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT nome,camimage,dano,hp, ahp, def, danoEspecial FROM Monstro where nodeck = false
    order by dano desc";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function MonstrosnoDeck()
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT nome,camimage,dano,hp, ahp, def, danoEspecial FROM Monstro where nodeck = true";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll()(PDO::FETCH_ASSOC);
    }
    function AddaoDeck($id)
    {
        try {
            $con = new conexao();
            $pdo = $con->pdo;
            $sql = "SELECT COUNT(*) FROM monstro WHERE nodeck = true";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $total = $stmt->fetchColumn();

            if ($total < 3) {
                $sql = "UPDATE monstro SET nodeck = true  WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id' => $id
                ]);
            }
        } catch (PDOException $e) {
            echo "Tire um monstro do deck para por outro" . $e->getMessage();
        }
    }

    function TiradoDeck($id)
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE monstro SET nodeck = false where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function setNome($nome)
    {
        $this->nome = $nome;
    }
    function setDano($dano)
    {
        $this->dano = $dano;
    }
    function setHp($hp)
    {
        $this->hp = $hp;
    }
    function setAhp($ahp)
    {
        $this->ahp = $ahp;
    }
    function setDef($def)
    {
        $this->def = $def;
    }
    function setImage($camimage)
    {
        $this->camimage = $camimage;
    }

    function getId()
    {
        return $this->id;
    }
    function getNome()
    {
        return $this->nome;
    }
    function getDano()
    {
        return $this->dano;
    }
    function getHp()
    {
        return $this->hp;
    }
    function getAhp()
    {
        return $this->ahp;
    }
    function getDef()
    {
        return $this->def;
    }
    function getImage()
    {
        return $this->camimage;
    }
}
?>
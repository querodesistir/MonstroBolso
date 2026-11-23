<?php
class Monstro
{
    private $id;
    private $nome;
    private $imagem;
    private $dano;
    private $hp;
    private $ahp; //hp atual
    private $def;
    private $prio; //prioridade
    private $danoEspecial;

    private $veloz;
    private $nodeck;
    function __construct($nome,$imagem, $dano, $hp, $ahp, $def, $prio, $danoEspecial,$veloz, $nodeck)
    {
        $this->nome = $nome;
        $this->imagem = $imagem;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
        $this->prio = $prio;
        $this->danoEspecial = $danoEspecial;
        $this ->veloz = $veloz;
        $this->nodeck = $nodeck;
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

        $sql = "SELECT nome,imagem,dano,hp, ahp, def, danoEspecial, veloz FROM Monstro where nodeck = false
    order by dano desc";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function MonstrosnoDeck()
    {
        $con = new conexao();
        $pdo = $con->pdo;

      $sql = "SELECT id, nome, imagem, dano, hp, ahp, def, prio, danoEspecial, veloz
      FROM Monstro
      WHERE nodeck = true";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    function setImage($imagem)
    {
        $this->imagem = $imagem;
    }
    function setVeloz($veloz){
        $this->veloz = $veloz;
    }
    function setNodeck($nodeck){
        $this->nodeck = $nodeck;
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
        return $this->imagem;
    }
    function getVeloz(){
        return $this->veloz;
    }
}
 function getNodeck(){
    return $this->nodeck;
}
 function getPrio(){
    return $this->prio;
}
function getDanoEspecial(){
    return $this->danoEspecial;
}
?>
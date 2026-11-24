
<?php

class Monstro
{
    private $id;
    private $nome;
    private $imagem;
    private $dano;
    private $hp;
    private $ahp;
    private $def;
    private $prio;
    private $danoEspecial;
    private $veloz;


    function __construct(
        $nome,
        $imagem,
        $dano,
        $hp,
        $ahp,
        $def,
        $prio,
        $danoEspecial,
        $veloz
    ) {
        $this->nome = $nome;
        $this->imagem = $imagem;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
        $this->prio = $prio;
        $this->danoEspecial = $danoEspecial;
        $this->veloz = $veloz;
    }

    function buscarMonstro()
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM monstro
                WHERE nome LIKE :nome";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(
            ':nome',
            '%' . trim($this->nome) . '%',
            PDO::PARAM_STR
        );

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function ListarMonstroForadoDeck($ideck, $idsu)
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT m.id, m.nome, m.imagem, m.dano, m.hp,
                       m.ahp, m.def, m.prio, m.danoEspecial, m.veloz
                FROM monstro m
                INNER JOIN deck d
                    ON d.ideck = :ideck
                    AND d.idsu = :idsu
                    AND d.descartado = 0
                WHERE (d.idm1 IS NULL OR d.idm1 <> m.id)
                AND (d.idm2 IS NULL OR d.idm2 <> m.id)
                AND (d.idm3 IS NULL OR d.idm3 <> m.id)
                ORDER BY m.dano DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function MonstrosnoDeck($ideck, $idsu)
    {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT m.id, m.nome, m.imagem, m.dano, m.hp,
                       m.ahp, m.def, m.prio, m.danoEspecial, m.veloz
                FROM deck d
                INNER JOIN monstro m
                    ON (
                        m.id = d.idm1
                        OR m.id = d.idm2
                        OR m.id = d.idm3
                    )
                WHERE d.ideck = :ideck
                AND d.idsu = :idsu
                AND d.descartado = 0
                ORDER BY FIELD(m.id, d.idm1, d.idm2, d.idm3)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // SETTERS
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

    function setVeloz($veloz)
    {
        $this->veloz = $veloz;
    }


    // GETTERS
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

    function getVeloz()
    {
        return $this->veloz;
    }

    function getPrio()
    {
        return $this->prio;
    }

    function getDanoEspecial()
    {
        return $this->danoEspecial;
    }
}

?>

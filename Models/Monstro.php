
<?php
class Monstro {

    private $id;
    private $nome;
    private $imagem;
    private $dano;
    private $hp;
    private $ahp;
    private $def;
    private $prio;
    private $danoEspecial;
    private $tipid;
    private $veloz;

    public function __construct(
        $nome = '',
        $imagem = null,
        $dano = null,
        $tipid = null,
        $hp = 100,
        $ahp = null,
        $def = null,
        $prio = null,
        $danoEspecial = null,
        $veloz = null,
        $id = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->imagem = $imagem;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
        $this->prio = $prio;
        $this->danoEspecial = $danoEspecial;
        $this->tipid = $tipid;
        $this->veloz = $veloz;
    }

    public function buscarMonstro() {

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

    public function ListarMonstros() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM monstro
                ORDER BY nome";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ListarMonstroForadoDeck($ideck, $idsu) {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT m.id, m.nome, m.imagem, m.dano, m.hp,
                       m.hp AS ahp, m.def, m.prio, m.danoEspecial,
                       m.tipid, t.elemento, m.veloz
                FROM monstro m
                INNER JOIN tipo t
                    ON t.tipid = m.tipid
                WHERE EXISTS (
                    SELECT 1
                    FROM deck d
                    WHERE d.ideck = :ideck
                    AND d.idsu = :idsu
                    AND d.descartado = 0
                )
                AND NOT EXISTS (
                    SELECT 1
                    FROM deck_monstro dm
                    WHERE dm.ideck = :ideck_monstros
                    AND dm.idm = m.id
                )
                ORDER BY m.dano DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu,
            ':ideck_monstros' => $ideck
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function MonstrosnoDeck($ideck, $idsu) {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT m.id, m.nome, m.imagem, m.dano, m.hp,
                       dm.ahp, m.def, m.prio, m.danoEspecial,
                       m.tipid, t.elemento, m.veloz, dm.posicao
                FROM deck d
                INNER JOIN deck_monstro dm
                    ON dm.ideck = d.ideck
                INNER JOIN monstro m
                    ON m.id = dm.idm
                INNER JOIN tipo t
                    ON t.tipid = m.tipid
                WHERE d.ideck = :ideck
                AND d.idsu = :idsu
                AND d.descartado = 0
                ORDER BY dm.posicao ASC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function getDano() {
        return $this->dano;
    }

    public function getHp() {
        return $this->hp;
    }

    public function getAhp() {
        return $this->ahp;
    }

    public function getDef() {
        return $this->def;
    }

    public function getPrio() {
        return $this->prio;
    }

    public function getDanoEspecial() {
        return $this->danoEspecial;
    }

    public function getTipid() {
        return $this->tipid;
    }

    public function getVeloz() {
        return $this->veloz;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    public function setDano($dano) {
        $this->dano = $dano;
    }

    public function setHp($hp) {
        $this->hp = $hp;
    }

    public function setAhp($ahp) {
        $this->ahp = $ahp;
    }

    public function setDef($def) {
        $this->def = $def;
    }

    public function setPrio($prio) {
        $this->prio = $prio;
    }

    public function setDanoEspecial($danoEspecial) {
        $this->danoEspecial = $danoEspecial;
    }

    public function setTipid($tipid) {
        $this->tipid = $tipid;
    }

    public function setVeloz($veloz) {
        $this->veloz = $veloz;
    }
}

?>
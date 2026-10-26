<?php 
 class Usuario{
    public $idsu;
    public $mail;
    public $senha;
    public $apelido;
    public $nivel;
    

 function __construct($mail, $senha,$apelido, $nivel){
    $this->mail = $mail;
    $this->senha = $senha;
    $this->apelido = $apelido;
    $this->nivel = $nivel;
 }   
 
 function cadasSu(){
   $con = new conexao();
   $pdo = $con->pdo;
   try {
     $sql = "INSERT INTO usuario(mail,senha, apelido, nivel) VALUES (:mail,:senha, :apelido, :nivel)";
  
     
    $stmt = $pdo->prepare($sql);

    $dados[':mail'] = $this->mail; // tirei o igual entre dados e [], funcionou
    $dados[':senha'] = password_hash($this->senha, PASSWORD_DEFAULT); //$dados[':senha'] = $this->senha; ia aconselhou trocar
    $dados[':apelido'] = $this->apelido;
    $dados[':nivel'] = $this->nivel;
    // 5. Executar passando os dados reais
    $stmt->execute($dados);

    $this->idsu = $pdo->lastInsertID(); 

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
   } //fim do cadasu

  function Login(){
    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "SELECT idsu, mail, senha, apelido, nivel FROM usuario WHERE mail = :mail";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':mail' => $this->mail]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($this->senha, $usuario['senha'])) {
     echo "<script>alert('senha ou usuarios incorretos. Já fez seu cadastro? ');</script>";
    header("location: login.php ");

    return false;
    }else{

    $this->idsu = $usuario['idsu'];
    $this->apelido = $usuario['apelido'];
    $this->nivel = $usuario['nivel'];

    return true;
    }
  }

   public function desaida() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header("Location: login.html");
    exit;
}

function lerPerfil() {
    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "SELECT mail, apelido, nivel FROM usuario WHERE idsu = :idsu";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idsu' => $this->idsu]); //porque tá executando isso dentro?

    return $stmt->fetch(PDO::FETCH_ASSOC);
}//fim ver perfil

function EditPerfil(){
   $con = new conexao();
   $pdo = $con->pdo;

   $sql = "UPDATE usuario set mail = :mail, apelido = :apelido where idsu = :idsu ";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      ':mail' => $this->mail, 
      ':apelido' => $this->apelido,
      ':idsu' => $this->idsu
   ]);

}

function mudaSenha($novaSenha) {
    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "UPDATE usuario SET senha = :senha WHERE idsu = :idsu";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
        ':idsu' => $this->idsu
    ]); }

function apagaConta(){
 $con = new conexao();
 $pdo = $con->pdo;

try {
        $pdo->beginTransaction();

        $sql = "DELETE FROM usuario WHERE idsu = :idsu";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idsu' => $this->idsu
        ]);

        if ($stmt->rowCount() === 0) {
            $pdo->rollBack();
            return false;
        }else{
        $pdo->commit();
        return true;
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    } 
                    } //fim de apagaConta
 
 function setMail($mail){
    $this->mail = $mail;
 }
 function setSen($mail){
    $this->senha = $senha;
 }
 function setApelido($apelido){
    $this->apelido = $apelido;
 }
 function setNivel($nivel){
    $this->nivel = $nivel;
 }
 function setIdsu($idsu){
    $this->idsu = $idsu;
 }
 function getMail(){
    return $this->mail;
 }
 function getSen(){
    return $this->senha;
 }
 function getApelido(){
    return $this->apelido;
 }
 function getNivel(){
    return $this->nivel;
 }
 function getIdsu(){
    return $this->idsu;
 }
 } //fim da classe
?>
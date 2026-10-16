<?php   //Cadastro e login são funçoes
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
 //ids são auto_incremente ou fazemos uma distribuição de numeros 
 
 function cadasSu(){
   try {
     $sql = "INSERT INTO clientes(mail,senha, apelido, nivel) VALUES (' :mail,:senha, :apelido, :nivel')";
   $con = new conexao();
   $pdo = $con->pdo;
     
    $stmt = $pdo->prepare($sql);

    $dados = []; 
    $dados[':mail'] = $this->mail; // tirei o igual entre dados e [], funcionou
    $dados[':senha'] = $this->senha;
    $dados[':apelido'] = $this->apelido;
    $dados[':nivel'] = $this->nivel;
    // 5. Executar passando os dados reais
    $stmt->execute($dados);

    $this->idsu = $pdo->lastInsertID(); //insere id autocomplete não precisa escrever no bd 

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
   } //fim do cadasu

  function Login(){
//checagem
  }

   function desaida(){
 echo "<script>alert('Até logo... Ou tarde 🥺');</script>";

session_destroy();
header("location: login.html");
   } //fim do desaida

function lerPerfil() {
    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "SELECT mail, apelido, nivel
            FROM usuario
            WHERE mail = :mail";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idsu' => $this->idsu]); //porque tá executando isso dentro?

    return $stmt->fetch(PDO::FETCH_ASSOC);
}//fim ver perfil

function EditPerfil(){
   $con = new conexao();
   $pdo = $con->pdo;

   $sql = "UPDATE usuario set mail = :mail, apelido = :apelido where idsu = :idsu ";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();

   return $stmt->fetch(PDO::FETCH_ASSOC);
}



//posso fazer uma restrição de que se não estiver logado não pode entrar na loja, quer?
 }
?>
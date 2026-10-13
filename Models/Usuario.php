<?php   //Cadastro e login são funçoes
 class Usuario{
    $idsu;
    $mail;
    $senha;
    $nível;
    $apelido;

 function __construct($mail, $senha, $nivel){
    $this->mail = $mail;
    $this->senha = $senha;
    $this->nivel = $nivel;
 }   
 //ids são auto_incremente ou fazemos uma distribuição de numeros 
 
 function cadasSu(){

   
   
   
   try {
     $sql = "INSERT INTO clientes(apelido, email, nivel) VALUES (:apelido, :mail, :nivel)"
   $con = new conexao();
   $pdo = $con->pdo;
     
    $stmt = $pdo->prepare($sql);

    $dados = [];
    $dados[':apelido'] = $this->apelido;
    // 5. Executar passando os dados reais
    $stmt->execute($dados);

    $this->id = $pdo->lastInsertID();

} catch (PDOException $e) {
    // Caso dê algum erro na conexão ou na query
    echo "Erro: " . $e->getMessage();
}


   } //fim do cadasu

  function Login(){

  }

   function desaida(){

alert("Até logo...Ou tarde 🥺 ");
session_destroy();
header("location: login.html");
   } //fim do desaida

//posso fazer uma restrição de que se não estiver logado não pode entrar na loja, quer?
 }
?>